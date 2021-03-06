<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:69:"F:\Work\os_class\public/../application/admin\view\trade_info\add.html";i:1615007129;s:59:"F:\Work\os_class\application\admin\view\layout\default.html";i:1611580234;s:56:"F:\Work\os_class\application\admin\view\common\meta.html";i:1611580234;s:58:"F:\Work\os_class\application\admin\view\common\script.html";i:1611580234;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">
<meta name="referrer" content="never">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<?php if(\think\Config::get('fastadmin.adminskin')): ?>
<link href="/assets/css/skins/<?php echo \think\Config::get('fastadmin.adminskin'); ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">
<?php endif; ?>

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>

    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !\think\Config::get('fastadmin.multiplenav') && \think\Config::get('fastadmin.breadcrumb')): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <?php if($auth->check('dashboard')): ?>
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                    <?php endif; ?>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Member_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-member_id" data-rule="required" data-source="MemberInfo/index" class="form-control selectpage" name="row[member_id]" type="text" value="" data-order-by='id' data-field='member_name'>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('User_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-user_id" data-rule="required" data-source="user/user/index" data-field="nickname" class="form-control selectpage" name="row[user_id]" type="text" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Pay_amount'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-pay_amount" data-rule="required" class="form-control" step="0.0001" name="row[pay_amount]" type="number" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Amount'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-amount" data-rule="required" class="form-control" step="0.0001" name="row[amount]" type="number" value="" disabled="true">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('First_user'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-first_user" data-rule="required" data-source="user/user/index" data-field="nickname" class="form-control selectpage" name="row[first_user_id]" type="text" disabled="true">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('First_rate'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-first_rate" data-rule="required" class="form-control" step="0.0001" name="row[first_rate]" type="number" value="" disabled="true">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('First_amount'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-first_amount" data-rule="required" class="form-control" step="0.0001" name="row[first_amount]" type="number" value="" disabled="true">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Second_user'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-second_user" data-rule="required" data-source="user/user/index" data-field="nickname" class="form-control selectpage" name="row[second_user_id]" type="text" disabled="true">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Second_rate'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-second_rate" data-rule="required" class="form-control" step="0.0001" name="row[second_rate]" type="number" value="" disabled="true">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Second_amount'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-second_amount" data-rule="required" class="form-control" step="0.0001" name="row[second_amount]" type="number" value="" disabled="true">
        </div>
    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
</form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version']); ?>"></script>
    </body>
</html>
