/**
 * Created by lxh on 2018/03/13.
 */

$(function () {

    $("#submitForm").validate(); 

    var tableUrl="index.php?p=admin&c=OrderForm&a=getList";

    window.operateEvents = {
        'click #orderform_edit': function (e, value, row, index) {

            openModel(row.orderform_id);
        },
        'click #orderform_view': function (e, value, row, index) {

            openModel(row.orderform_id);
        },

        'click #orderform_delete': function (e, value, row, index) {
            parent.layer.confirm('确认要删除该商品？', {
                btn: ['确定','返回'] //按钮
            }, function(){
                goodsDelete(row.orderform_id ,'index.php?p=admin&c=orderform&a=del');
            }, function(){
            });
        },
       
    }


     function  goodsDelete(orderform_id,url){
        $.ajax({
            type:'post',
            url:url,
            data: {orderform_id:orderform_id},
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
        //search: true,  //是否显示搜索框功能
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
               /* order_number:$('#order_number').val(),
                member_id:$('#member_id').val(),
                submission_time:$('#submission_time').val(),*/
                keyword:$('#keyword').val(),
                sortName: params.sortName,
                sortOrder: params.sortOrder,
                offset: params.offset,
            };
            return temp;
        },
        columns:[
            {
                field:'orderform_id',
                title:'ID',
            },
            {
                field:'order_number',
                title:'订单号',
            },
            {
                field:'submission_time',
                title:'提交时间',
            },
            {
                field:'member_id',
                title:'购买用户',
            },
            {
                field:'admin_id',
                title:'处理管理员',
            },
            {
                field:'processing_time',
                title:'处理时间',
            },

            {
                field:'status',
                title:'状态',

               /* formatter:function (index,row) {
                    var e;
                    e='<img src='+row.photo+' width="50px" >'
                    return e;
                }*/
            },

            /*{
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
            },*/

            {
                field:'operate',
                title:'操作',
                events:operateEvents,
                formatter:function (index,row) {
                    var edit = '<a href="#" id="orderform_edit" class="btn btn-primary btn-sm">处理</a> ';
                    var cancel = '<a href="#" id="orderform_cancel" class="btn btn-primary btn-sm">取消</a> ';
                    var e = '<a href="#" id="orderform_view" class="btn btn-primary btn-sm">查看</a> ';
                    var s= '<a href="#" id="orderform_delete" class="btn btn-danger btn-sm">删除</a> ';
                    return edit+cancel+e+s;
                }
            }
        ],
    });


    //打开会员详情model


    function openModel(orderform_id){

        $("#alert").hide();

        if(orderform_id>0){
            $("#modelTitle").html('修改商品');
            $("#orderform_id").val(orderform_id);
            $("#submitForm").attr('action','index.php?p=admin&c=orderform&a=editOrderForm');
             //$(".none").css("display",'block');
            bindEditData(orderform_id);
            //$("#Modal").modal('show');
        }else{
           // $("#order_number").val(" ");
            $("#member_id").val(" ");
           // $("#submission_time").val(" ");
            $("#admin_id").val(" ");
            $("#processing_time").val(" ");
            //$("#qrpwd").val(" ");
           // $(".store-img-read").attr("src"," ");
            //$(".none").css("display",'none');
            $("#modelTitle").html('新增商品');
            $("#orderform_id").val(0);
            $("#submitForm").attr('action','index.php?p=admin&c=Goods&a=addGoods');
            $("#Modal").modal('show');
        }


    }
  
    //获取会员详情单条数据
    function bindEditData(orderform_id){

       $.ajax({
           url:'index.php?p=admin&c=orderform&a=getOne',

           data:{orderform_id:orderform_id},

           type:'post',

            success:function (data) {
                var data = JSON.parse(data);
                $("#order_number").val(data.order_number);
                $("#member_id").val(data.member_id);
                $("#submission_time").val(data.submission_time);
                $("#admin_id").val(data.admin_id);
                $("#processing_time").val(data.processing_time);
                //$(".store-img-read").attr("src",data.photo);
                //$(".sexrows input[data-sex='"+data.sex+"']").attr("checked","checked");
                //$(".is_admin input[data-admin='"+data.is_admin+"']").attr("checked","checked");
                //$("#group_id option[data-group-id='"+data.group_id+"']").attr("selected","selected");
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




