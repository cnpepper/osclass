# 项目使用的SQL语句

# 函数 生成唯一单号

DROP FUNCTION IF EXISTS `f_app_seq`;
DELIMITER $$

CREATE FUNCTION `f_app_seq`(`in_seq_name` VARCHAR(20)) RETURNS VARCHAR(20) CHARSET utf8mb4
    DETERMINISTIC
BEGIN
DECLARE v_d VARCHAR(20);
DECLARE v_app_no VARCHAR(20);
DECLARE v_app_code VARCHAR(20);
DECLARE v_temp VARCHAR(20);
SET v_d=DATE_FORMAT(NOW(),'%Y%m%d');
    UPDATE app_sequence SET seq_date=v_d,seq_value=0  WHERE seq_name=in_seq_name AND seq_date<>v_d;  
    UPDATE app_sequence SET seq_value=LAST_INSERT_ID(seq_value+1)  WHERE seq_name=in_seq_name; 
    
SET v_app_code = LAST_INSERT_ID();   
SET v_app_code = LPAD(v_app_code,4,'0') ;
    SELECT app_no INTO v_app_no FROM app_sequence WHERE seq_name = in_seq_name LIMIT 1;
SET v_temp='';
SET v_temp=CONCAT(v_app_no,v_d,v_app_code);
RETURN  v_temp;
    END$$

DELIMITER ;

# 存储过程 创建订单
DROP PROCEDURE IF EXISTS `TradeOrderCreate`;
DELIMITER $$

CREATE PROCEDURE `TradeOrderCreate`(IN V_USER_ID INTEGER,IN V_MEMBER_ID INTEGER,IN V_PAY_AMOUNT DECIMAL(19,4))
BEGIN
    DECLARE V_FIRST_USER_ID INT(10) DEFAULT 0;
    DECLARE V_SECOND_USER_ID INT(10) DEFAULT 0;
    DECLARE V_FIRST_RATE DECIMAL(19,4) DEFAULT 0.0000;
    DECLARE V_SECOND_RATE DECIMAL(19,4) DEFAULT 0.0000;
    DECLARE V_MEMBER_PRICE DECIMAL(19,4) DEFAULT 0.0000;
    
    DECLARE V_NOT_FIND INT(10) DEFAULT 0;
    DECLARE V_ERROR_CODE INT(10) DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET V_NOT_FIND = 1;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET V_ERROR_CODE = 1;
    
	/* 查询一级和二级分销的比例*/
    SELECT fu.`parent_id`,fm.`first_rate` INTO V_FIRST_USER_ID,V_FIRST_RATE FROM `fa_user` fu
    LEFT JOIN `fa_member_info` fm ON fm.`id` = fu.`member_id`
    WHERE fu.id = V_USER_ID;
    

    SELECT fu.`parent_id`,fm.`first_rate` INTO V_SECOND_USER_ID,V_SECOND_RATE FROM `fa_user` fu
    LEFT JOIN `fa_member_info` fm ON fm.`id` = fu.`member_id`
    WHERE fu.id = V_FIRST_USER_ID;
    
	/* 查询购买的会员等级的实际价格 */
    SELECT price INTO V_MEMBER_PRICE FROM `fa_member_info` WHERE id = V_MEMBER_ID;
    

    START TRANSACTION;
	

    SET @trade_no = '';
    SELECT `f_app_seq`('trade_no') INTO @trade_no;
    
    INSERT INTO `fa_trade_info`(
    trade_no,
    member_id,
    user_id,
    amount,
    pay_amount,
    discount_amount,
    first_user,
    first_rate,
    first_amount,
    second_user,
    second_rate,
    second_amount,
    createtime
    ) VALUES(@trade_no,V_MEMBER_ID,V_USER_ID,V_MEMBER_PRICE,V_PAY_AMOUNT,(V_PAY_AMOUNT-V_MEMBER_PRICE),
    V_FIRST_USER_ID,V_FIRST_RATE,(V_FIRST_RATE*V_PAY_AMOUNT),
    V_SECOND_USER_ID,V_SECOND_RATE,(V_SECOND_RATE*V_PAY_AMOUNT),
    NOW());
    
	/* 根据查询到的比例计算提成金额并更新用户的分成总金额 */
    UPDATE `fa_user` SET share_amount = (share_amount+(V_PAY_AMOUNT*V_FIRST_RATE))  WHERE id = V_FIRST_USER_ID;
    UPDATE `fa_user` SET share_amount = (share_amount+(V_PAY_AMOUNT*V_SECOND_RATE))  WHERE id = V_SECOND_USER_ID;
    
	/* 更新当前购买用户的会员等级 todo 未处理覆盖的情况 */
    UPDATE `fa_user` SET member_id = V_MEMBER_ID  WHERE id = V_USER_ID;
    
    IF V_ERROR_CODE THEN
	ROLLBACK;
    ELSE
	COMMIT;
    END IF;
	
	SELECT @trade_no as trade_no;
    
    END$$

DELIMITER ;