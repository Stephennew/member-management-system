/**
 * Created by lxh on 2018/03/13.
 */

$(function () {

    $("#submitForm").validate();
    var tableUrl="index.php?p=admin&c=Plans&a=getList";

    window.operateEvents = {
        'click #plan_edit': function (e, value, row, index) {

            openModel(row.plan_id);
        },
        'click #plan_delete': function (e, value, row, index) {
            parent.layer.confirm('确认删除？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                plansDelete(row.plan_id,'index.php?p=admin&c=Plans&a=del');
            }, function(){
            });

        }
       
    }

   
    function openOrClose(id,url){
    
        $.ajax({
            type:'post',
            url:url,
            data: {id:id},
            success:function (data) {
                if(data.code==100){
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
        queryParams: function getParams(params) {
            var temp = {
                limit: params.limit,
                name:$('#Keyword').val(),
                money:$('#Keywords').val(),
                sortName: params.sortName,
                sortOrder: params.sortOrder,
                offset: params.offset,
            };
            return temp;
        },
    
        columns:[
            {
                field:'plan_id',
                title:'行号',
            },
            {
                field:'name',
                title:'套餐名称',
            },
            {
                field:'des',
                title:'套餐描述',
            },
            {
                field:'money',
                title:'套餐金额',
            },
            {
                field:'status',
                title:'套餐状态',
                formatter:function (index,row) {
                    var e;
                    if(row.status==1){
                        e='上线';
                    }else if(row.status==0){
                        e='下线';
                    }
                    return e;
                }
            },

            {
                field:'operate',
                title:'操作',
                events:operateEvents,
                formatter:function (index,row) {
                    var e = '<a href="#" id="plan_edit" class="btn btn-primary btn-sm">修改</a> ';
                    var s= '<a href="#" id="plan_delete" class="btn btn-danger btn-sm">删除</a> ';
                    return e+s;
                }
            }
        ],
    });


    //打开会员详情model


     function openModel(plan_id){

        $("#alert").hide();

        if(plan_id>0){
            $("#modelTitle").html('修改套餐');
            $("#plan_id").val(plan_id);
            $("#submitForm").attr('action','index.php?p=admin&c=Plans&a=editPlans');
            bindEditData(plan_id);
        }else{
            /*$("#user_name").val(" ");
            $("#realname").val(" ");*/
            $("#name").val(" ");
            $("#des").val(" ");
            $("#money").val(" ");
            $("#status").val(" ");
            $("#modelTitle").html('添加套餐');
            $("#plan_id").val(0);
             $("#submitForm").attr('action','index.php?p=admin&c=Plans&a=addPlans');
        }

        $("#Modal").modal('show');

    }
  
    //获取会员详情单条数据
    function bindEditData(plan_id){

       $.ajax({
           url:'index.php?p=admin&c=Plans&a=getOne',

           data:{plan_id:plan_id},

           type:'post',

            success:function (data) {
                var data = JSON.parse(data);
                console.log(data);
                $("#name").val(data.name);
                $("#des").val(data.des);
                $("#money").val(data.money);
                //$("#status").val(data.status);
                $("#statusrows input[data-status='"+data.status+"']").attr("checked","checked");

               
            }
       });
    }
    function plansDelete(plan_id,url){
        $.ajax({
            type:'post',
            url:url,
            data: {plan_id:plan_id},
            success:function (data) {

                if(data){
                    parent.layer.msg('删除成功');
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




