/**
 * Created by lxh on 2018/03/13.
 */

$(function () {

    $("#submitForm").validate();
    var tableUrl="index.php?p=admin&c=RechargeRule&a=getList";

    window.operateEvents = {
        'click #edit': function (e, value, row, index) {

            openModel(row.recharge_rule_id);
        },
        'click #delete': function (e, value, row, index) {
            parent.layer.confirm('确认删除？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                Delete(row.recharge_rule_id

                    ,'index.php?p=admin&c=RechargeRule&a=del');
            }, function(){
            });

        },
       
    }

   
    function openOrClose(id,url){
    
        $.ajax({
            type:'post',
            url:url,
            data: {id:id},
            success:function (data) {
                if(data){
                    $('#ListTable').bootstrapTable(
                        "refresh",
                        {
                            url:tableUrl
                        }
                    );
                }else{
    
                    alert(data.message);
    
                }
            }
        });
    }

    $('#ListTable').bootstrapTable({
        url: tableUrl,
        // search: true,  //是否显示搜索框功能
        pagination: true,  //是否分页
        showRefresh: true, //是否显示刷新功能
        showToggle: true,
        showColumns: true,
        iconSize: 'outline',
        sidePagination: "server",
        // toolbar: '#exampleTableEventsToolbar', 可以在table上方显示的一条工具栏，
        icons: {
            refresh: 'glyphicon-repeat',
            toggle: 'glyphicon-list-alt',
            columns: 'glyphicon-list'
        },
    
        columns:[
            {
                field:'recharge_rule_id',
                title:'行号',
            },

            {
                field:'recharge_money',
                title:'充值金额',
            },

            {
                field:'recharge_send',
                title:'赠送金额',
            },
            {
                field:'remarks',
                title:'规则说明',
            },

            {
                field:'operate',
                title:'操作',
                events:operateEvents,
                formatter:function (index,row) {
                    var e = '<a href="#" id="edit" class="btn btn-primary btn-sm">修改</a> ';
                    var s= '<a href="#" id="delete" class="btn  btn-danger">删除</a> ';
                    return e+s;
                }
            }
        ],
    });


    //打开会员详情model


     function openModel(id){

        $("#alert").hide();

        if(id>0){
            $("#modelTitle").html('充值规则修改');
            $("#recharge_rule_id").val(id);
            $("#submitForm").attr('action','index.php?p=admin&c=RechargeRule&a=editRechargeRule');
            bindEditData(id);
        }else{
            
            $("#recharge_money").val(" ");
           $("#recharge_send").val(" ");
           $("#remarks").val(" ");
            $("#modelTitle").html('充值规则添加');
            $("#recharge_rule_id").val(0);
             $("#submitForm").attr('action','index.php?p=admin&c=RechargeRule&a=addRechargeRule');
        }

        $("#Modal").modal('show');

    }
  
    //获取会员详情单条数据
    function bindEditData(id){

       $.ajax({
           url:'index.php?p=admin&c=RechargeRule&a=getOne',

           data:{recharge_rule_id:id},

           type:'post',

            success:function (data) {
               //console.log(data);
                var data = JSON.parse(data);
               $("#recharge_money").val(data.recharge_money);
               $("#recharge_send").val(data.recharge_send);
               $("#remarks").val(data.remarks);
            }
       });
    }
    function Delete(id,url){
        $.ajax({
            type:'post',
            url:url,
            data: {recharge_rule_id:id},
            success:function (data) {
                if(data){
                    $("#Modal").modal('hide');
                    $('#ListTable').bootstrapTable(

                        "refresh",
                        {
                            url:tableUrl
                        }
                    );
                }
            }
        });
    }


    $('#openformbtn').click(function () {   openModel(0);   });

    $("#cancel").click(function () {    $("#Modal").modal('hide'); });

    $("#select-condition").click(function(){$('#ListTable').bootstrapTable('refresh');});

    $("#sbumitbtn").click(function () {


        var flag = $("#submitForm").valid();
        if(!flag){
            //没有通过验证
            return;
        }
        $.ajax({type:'post',url:$("#submitForm").attr('action'),data: $("#submitForm").serialize(),
            success:function (data) {

                if(data){

                    $("#Modal").modal('hide');

                    window.location.reload();
                }else{
                    $("#alert-message").html(data.message);
                    $("#alert").show();

                }

            }

        });

    });

});




