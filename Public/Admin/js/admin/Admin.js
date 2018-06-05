/**
 * Created by lxh on 2018/03/13.
 */

$(function () {

    $("#submitForm").validate(); 

    var tableUrl="index.php?p=admin&c=admin&a=getList";

    window.operateEvents = {
        'click #admin_edit': function (e, value, row, index) {

            openModel(row.admin_id);
        },

        'click #admin_delete': function (e, value, row, index) {
            parent.layer.confirm('确认要删除该会员？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                adminDelete(row.admin_id ,'index.php?p=admin&c=admin&a=del');
            }, function(){
            });
        },
       
    }


     function  adminDelete(admin_id,url){
        $.ajax({
            type:'post',
            url:url,
            data: {admin_id:admin_id},
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
                group_id:$('#admin_group_id').val(),
                sex:$('#admin_sex').val(),
                keyword:$('#keyword').val(),
                sortName: params.sortName,
                sortOrder: params.sortOrder,
                offset: params.offset,
            };
            return temp;
        },
        columns:[
            {
                field:'admin_id',
                title:'ID',
            },
            {
                field:'user_name',
                title:'昵称',
            },
            {
                field:'realname',
                title:'真实姓名',
            },

            {
                field:'icon',
                title:'头像',

                formatter:function (index,row) {
                    var e;
                    e='<img src='+row.icon+' width="50px" >'
                    return e;
                }
            },

            
            {
                field:'sex',
                title:'会员性别',
                formatter:function (index,row) {
                    var e;
                    if(row.sex==0){ 
                        e='未知';
                    }else if(row.sex==1){
                        e='男';
                    }else if(row.sex==2){
                        e='女';
                    }
                    return e;
                }
            },
            {
                field:'telphone',
                title:'联系电话',
            },

            {
                field:'group_name',
                title:'所属部门',
            },
            {
                field:'last_login',
                title:'最后登录时间',
            },

            {
                field:'last_login_ip',
                title:'最后登录ip',
            },

            {
                field:'operate',
                title:'操作',
                events:operateEvents,
                formatter:function (index,row) {
                    var e = '<a href="#" id="admin_edit" class="btn btn-primary btn-sm">编辑</a> ';
                    var s= '<a href="#" id="admin_delete" class="btn btn-danger btn-sm">删除</a> ';
                    return e+s;
                }
            }
        ],
    });


    //打开会员详情model


    function openModel(admin_id){

        $("#alert").hide();

        if(admin_id>0){
            $("#modelTitle").html('管理员修改');
            $("#admin_id").val(admin_id);
            $("#submitForm").attr('action','index.php?p=admin&c=admin&a=editAdmin');
             $(".none").css("display",'block');
            bindEditData(admin_id);
        }else{
            $("#user_name").val(" ");//将弹框内容置为空
            $("#realname").val(" ");
            $("#telphone").val(" ");
            $("#password").val(" ");
            $("#qrpwd").val(" ");
            $(".store-img-read").attr("src"," ");
            $(".none").css("display",'none');
            $("#modelTitle").html('新增管理员');
            $("#member_id").val(0);
             $("#submitForm").attr('action','index.php?p=admin&c=Admin&a=addAdmin');
        }

        $("#Modal").modal('show');

    }
  
    //获取会员详情单条数据
    function bindEditData(admin_id){

       $.ajax({
           url:'index.php?p=admin&c=admin&a=getFindData',

           data:{admin_id:admin_id},

           type:'post',

            success:function (data) {
                var data = JSON.parse(data);
                $("#user_name").val(data.user_name);
                $("#realname").val(data.realname);
                $("#telphone").val(data.telphone);
                $("#remarks").val(data.remarks);
                $(".store-img-read").attr("src",data.icon);
                $(".sexrows input[data-sex='"+data.sex+"']").attr("checked","checked");
                $(".is_admin input[data-admin='"+data.is_admin+"']").attr("checked","checked");
                $("#group_id option[data-group-id='"+data.group_id+"']").attr("selected","selected");
               
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

        var formData = new FormData($("#submitForm")[0]);
        $.ajax({
            type:'post',
            url:$("#submitForm").attr('action'),
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success:function (data) {

                var data= JSON.parse(data);

                if(data.code){

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




