/**
 * Created by lxh on 2018/03/13.
 */

$(function () {

    $("#submitForm").validate();
    var tableUrl="index.php?p=admin&c=Codes&a=getList";

    window.operateEvents = {
        'click #code_edit': function (e, value, row, index) {

            openModel(row.code_id);
        },
        'click #code_delete': function (e, value, row, index) {
            parent.layer.confirm('确认删除？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                codeDelete(row.code_id

                    ,'index.php?p=admin&c=Codes&a=del');
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
                code:$("#searchcode").val(),
                sortOrder: params.sortOrder,
                offset: params.offset,
            };
            return temp;
        },
        columns:[
            {
                field:'code_id',
                title:'行号',
            },
             {
                field:'code',
                title:'代金券',
            },
            {
                field:'user_id',
                title:'所属会员',
            },
            {
                field:'money',
                title:'金额',
            },


            {
                field:'operate',
                title:'操作',
                events:operateEvents,
                formatter:function (index,row) {
                    var e = '<a href="#" id="code_edit" class="btn btn-primary btn-sm">修改</a> ';
                    var s= '<a href="#" id="code_delete" class="btn btn-primary btn-sm">删除</a> ';
                    return e+s;
                }
            }
        ],
    });


    //打开会员详情model


     function openModel(code_id){

        $("#alert").hide();

        if(code_id>0){
            $("#modelTitle").html('代金券修改');

            $("#code_id").val(code_id);
            $("#submitForm").attr('action','index.php?p=admin&c=Codes&a=editCodes');
            $(".editnone").css("display","none");
            $(".addnone").css("display","block");
            bindEditData(code_id);
        }else{
            $("#num").val(" ");
            $("#username").val(" ");
            $("#money").val(" ");
            $("#modelTitle").html('添加添加代金券');
            $("#group_id").val(0);
            $(".addnone").css("display","none");
            $(".editnone").css("display","block");
             $("#submitForm").attr('action','index.php?p=admin&c=Codes&a=addCodes');
        }

        $("#Modal").modal('show');

    }
  
    //获取会员详情单条数据
    function bindEditData(code_id){

       $.ajax({
           url:'index.php?p=admin&c=Codes&a=getOne',

           data:{code_id:code_id},

           type:'post',

            success:function (data) {
               //console.log(data);
                var data = JSON.parse(data);
               $("#code").val(data.code);
                $("#name").val(data.user_id);
                $("#money").val(data.money);
            }
       });
    }
    function codeDelete(code_id,url){
        $.ajax({
            type:'post',
            url:url,
            data: {code_id:code_id},
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




