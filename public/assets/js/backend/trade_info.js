const { ajax } = require("jquery");

define(["jquery", "bootstrap", "backend", "table", "form"], function (
  $,
  undefined,
  Backend,
  Table,
  Form
) {
  var Controller = {
    index: function () {
      // 初始化表格参数配置
      Table.api.init({
        extend: {
          index_url: "trade_info/index" + location.search,
          add_url: "trade_info/add",
          edit_url: "trade_info/edit",
          del_url: "trade_info/del",
          multi_url: "trade_info/multi",
          import_url: "trade_info/import",
          table: "trade_info",
        },
      });

      var table = $("#table");

      // 初始化表格
      table.bootstrapTable({
        url: $.fn.bootstrapTable.defaults.extend.index_url,
        pk: "id",
        sortName: "id",
        columns: [
          [
            { checkbox: true },
            { field: "id", title: __("Id") },
            { field: "trade_no", title: __("Trade_no"), operate: "LIKE" },
            {
              field: "member_info.member_name",
              title: __("Memberinfo.member_name"),
              operate: "LIKE",
            },
            {
              field: "user.nickname",
              title: __("Userid.name"),
              operate: "LIKE",
            },
            //{ field: "member_id", title: __("Member_id") },
            //{ field: "user_id", title: __("User_id") },
            { field: "amount", title: __("Amount"), operate: "BETWEEN" },
            {
              field: "pay_amount",
              title: __("Pay_amount"),
              operate: "BETWEEN",
            },
            { field: "first_user.nickname", title: __("First_user") },
            {
              field: "first_rate",
              title: __("First_rate"),
              operate: "BETWEEN",
            },
            {
              field: "first_amount",
              title: __("First_amount"),
              operate: "BETWEEN",
            },
            { field: "second_user.nickname", title: __("Second_user") },
            {
              field: "second_rate",
              title: __("Second_rate"),
              operate: "BETWEEN",
            },
            {
              field: "second_amount",
              title: __("Second_amount"),
              operate: "BETWEEN",
            },
            {
              field: "createtime",
              title: __("Createtime"),
              operate: "RANGE",
              addclass: "datetimerange",
              autocomplete: false,
              formatter: Table.api.formatter.datetime,
            },
            {
              field: "updatetime",
              title: __("Updatetime"),
              operate: "RANGE",
              addclass: "datetimerange",
              autocomplete: false,
              formatter: Table.api.formatter.datetime,
            },
            { field: "user.openid", title: __("User.openid"), operate: "LIKE" },
            {
              field: "operate",
              title: __("Operate"),
              table: table,
              events: Table.api.events.operate,
              formatter: Table.api.formatter.operate,
            },
          ],
        ],
      });

      // 为表格绑定事件
      Table.api.bindevent(table);
    },
    add: function () {
      Controller.api.bindevent();
    },
    edit: function () {
      Controller.api.bindevent();
    },
    api: {
      bindevent: function () {
        // 当购买的等级变化时，对应变化应收金额
        $(document).on("change", "#c-member_id", function () {
            var member_id = $("#c-member_id").val();
          $.post(
            "TradeInfo/MemberAmountQuery",
            {
                member_id:member_id
            },
            function (data, status) {
              if(data.code){
                $("#c-pay_amount").val(0);
                $("#c-amount").val(data.data.amount);
              }
            }
          );
        });

        // 当购买人发生变化时，对应改变一级二级分销人员
        $(document).on("change", "#c-user_id", function () {
            var user_id = $("#c-user_id").val();
          $.post(
            "TradeInfo/ShareUserQuery",
            {
                user_id:user_id
            },
            function (data, status) {
              if(data.code){
                console.log(data.data)
                $('#c-first_user').val(data.data.first_user_id);
                $("#c-first_user").selectPageRefresh();
                $("#c-first_rate").val(data.data.first_rate);
                $("#c-second_user").val(data.data.second_user_id);
                $("#c-second_user").selectPageRefresh();
                $("#c-second_rate").val(data.data.second_rate);
                
              }
            }
          );
        });

        Form.api.bindevent($("form[role=form]"));
      },
    },
  };
  return Controller;
});
