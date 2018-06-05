/**
 * Created by lxh on 2018/03/13.
 */

$(function () {

    $("#submitForm").validate();
    var tableUrl="index.php?p=admin&c=Order&a=getList";

    window.operateEvents = {
        'click #order_edit': function (e, value, row, index) {

            openModel(row.order_id);
        },
        'click #order_delete': function (e, value, row, index) {
            parent.layer.confirm('确认删除？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                groupDelete(row.order_id

                    ,'index.php?p=admin&c=Order&a=del');
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
                field:'order_id',
                title:'行号',
            },
            {
                field:'realname',
                title:'预约人',
            },
            {
                field:'telphone',
                title:'电话',
            },
            {
                field:'barber',
                title:'理发师',
            },
            {
                field:'content',
                title:'备注',
            },
            {
                field:'date',
                title:'时间',
            },
            {
                field:'status',
                title:'状态',
            },

            {
                field:'reply',
                title:'回复',
            },
            {
                field:'operate',
                title:'操作',
                events:operateEvents,
                formatter:function (index,row) {
                    var e = '<a href="#" id="order_edit" class="btn btn-primary btn-sm">回复</a> ';
                    var s= '<a href="#" id="order_delete" class="btn btn-primary btn-sm">删除</a> ';
                    return e+s;
                }
            }

        ],
    });


    //打开会员详情model


     function openModel(order_id){

        $("#alert").hide();

        if(order_id>0){
            $("#modelTitle").html('部门修改');
            $("#order_id").val(order_id);
            $("#submitForm").attr('action','index.php?p=admin&c=Order&a=editOrder');
            bindEditData(order_id);
        }else{
            /*$("#user_name").val(" ");
            $("#realname").val(" ");*/
            $("#name").val(" ");
            $("#modelTitle").html('添加部门');
            $("#group_id").val(0);
             $("#submitForm").attr('action','index.php?p=admin&c=Group&a=addGroup');
        }

        $("#Modal").modal('show');

    }
  
    //获取会员详情单条数据
    function bindEditData(order_id){

       $.ajax({
           url:'index.php?p=admin&c=Order&a=getOne',

           data:{order_id:order_id},

           type:'post',

            success:function (data) {
               //console.log(data);
                var data = JSON.parse(data);
               $("#reply").val(data.reply);
            }
       });
    }
    function groupDelete(order_id,url){
        $.ajax({
            type:'post',
            url:url,
            data: {order_id:order_id},
            success:function (data) {

                if(data){
                    parent.layer.msg('确认删除?');
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




