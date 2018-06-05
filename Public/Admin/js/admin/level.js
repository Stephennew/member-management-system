/**
 * Created by lxh on 2018/03/13.
 */

$(function () {

    $("#submitForm").validate();
    var tableUrl="index.php?p=admin&c=Level&a=getList";

    window.operateEvents = {
        'click #level_edit': function (e, value, row, index) {

            openModel(row.level_id);
        },
        'click #level_delete': function (e, value, row, index) {
            parent.layer.confirm('确认删除？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                levelDelete(row.level_id,'index.php?p=admin&c=Level&a=del');
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
                field:'level_id',
                title:'行号',
            },
            {
                field:'caste',
                title:'vip等级',
            },
            {
                field:'discount',
                title:'折扣',
            },
            {
                field:'condition',
                title:'达成条件',
            },

            {
                field:'operate',
                title:'操作',
                events:operateEvents,
                formatter:function (index,row) {
                    var e = '<a href="#" id="level_edit" class="btn btn-primary btn-sm">修改</a> ';
                    // var s= '<a href="#" id="level_delete" class="btn btn-primary btn-sm">删除</a> ';
                    return e;
                }
            }
        ],
    });


    //打开会员详情model


     function openModel(level_id){

        $("#alert").hide();

        if(level_id>0){
            $("#modelTitle").html('修改会员等级');
            $("#level_id").val(level_id);
            $("#submitForm").attr('action','index.php?p=admin&c=Level&a=editLevel');
            bindEditData(level_id);
        }else{
            /*$("#user_name").val(" ");
            $("#realname").val(" ");*/
            $("#caste").val(" ");
            $("#discount").val(" ");
            $("#condition").val(" ");
            $("#modelTitle").html('添加会员等级');
            $("#plan_id").val(0);
             $("#submitForm").attr('action','index.php?p=admin&c=Level&a=addLevel');
        }

        $("#Modal").modal('show');

    }
  
    //获取会员详情单条数据
    function bindEditData(level_id){

       $.ajax({
           url:'index.php?p=admin&c=Level&a=getOne',

           data:{level_id:level_id},

           type:'post',

            success:function (data) {
                var data = JSON.parse(data);
                console.log(data);
                $("#caste").val(data.caste);
                $("#discount").val(data.discount);
                $("#condition").val(data.condition);

               
            }
       });
    }
    function levelDelete(level_id,url){
        $.ajax({
            type:'post',
            url:url,
            data: {level_id:level_id},
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




