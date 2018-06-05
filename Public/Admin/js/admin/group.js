/**
 * Created by lxh on 2018/03/13.
 */

$(function () {

    $("#submitForm").validate();
    var tableUrl="index.php?p=admin&c=Group&a=getList";

    window.operateEvents = {
        'click #group_edit': function (e, value, row, index) {

            openModel(row.group_id);
        },
        'click #group_delete': function (e, value, row, index) {
            parent.layer.confirm('确认删除？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                groupDelete(row.group_id

                    ,'index.php?p=admin&c=Group&a=del');
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
        queryParams: function getParams(params) {
            var temp = {
                limit: params.limit,
                name:$('#group_name').val(),
                sortName: params.sortName,
                sortOrder: params.sortOrder,
                offset: params.offset,
            };
            return temp;
        },
    
        columns:[
            {
                field:'group_id',
                title:'行号',
            },
             {
                field:'name',
                title:'部门名称',
            },

            {
                field:'operate',
                title:'操作',
                events:operateEvents,
                formatter:function (index,row) {
                    var e = '<a href="#" id="group_edit" class="btn btn-primary btn-sm">修改</a> ';
                    var s= '<a href="#" id="group_delete" class="btn btn-danger btn-sm">删除</a> ';
                    return e+s;
                }
            }
        ],
    });


    //打开会员详情model


     function openModel(group_id){

        $("#alert").hide();

        if(group_id>0){
            $("#modelTitle").html('部门修改');
            $("#group_id").val(group_id);
            $("#submitForm").attr('action','index.php?p=admin&c=Group&a=editGroup');
            bindEditData(group_id);
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
    function bindEditData(group_id){

       $.ajax({
           url:'index.php?p=admin&c=Group&a=getOne',

           data:{group_id:group_id},

           type:'post',

            success:function (data) {
               //console.log(data);
                var data = JSON.parse(data);
               $("#name").val(data.name);
            }
       });
    }
    function groupDelete(group_id,url){
        $.ajax({
            type:'post',
            url:url,
            data: {group_id:group_id},
            success:function (data) {
                if(data){
                    parent.layer.msg("删除成功");
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




