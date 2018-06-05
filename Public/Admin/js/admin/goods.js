/**
 * Created by lxh on 2018/03/13.
 */

$(function () {

    $("#submitForm").validate(); 

    var tableUrl="index.php?p=admin&c=Goods&a=getList";

    window.operateEvents = {
        'click #goods_edit': function (e, value, row, index) {

            openModel(row.goods_id);
        },

        'click #goods_delete': function (e, value, row, index) {
            parent.layer.confirm('确认要删除该商品？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                goodsDelete(row.goods_id ,'index.php?p=admin&c=goods&a=del');
            }, function(){
            });
        },
       
    }


     function  goodsDelete(goods_id,url){
        $.ajax({
            type:'post',
            url:url,
            data: {goods_id:goods_id},
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
                field:'goods_id',
                title:'ID',
            },
            {
                field:'name',
                title:'商品名称',
            },
            {
                field:'price',
                title:'积分价格',
            },
            {
                field:'des',
                title:'简介',
            },
            {
                field:'inventory',
                title:'库存',
            },
            {
                field:'status',
                title:'状态',
            },

            {
                field:'photo',
                title:'图片',

                formatter:function (index,row) {
                    var e;
                    e='<img src='+row.photo+' width="50px" >'
                    return e;
                }
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
                    var e = '<a href="#" id="goods_edit" class="btn btn-primary btn-sm">编辑</a> ';
                    var s= '<a href="#" id="goods_delete" class="btn btn-danger btn-sm">删除</a> ';
                    return e+s;
                }
            }
        ],
    });


    //打开会员详情model


    function openModel(goods_id){

        $("#alert").hide();

        if(goods_id>0){
            $("#modelTitle").html('修改商品');
            $("#goods_id").val(goods_id);
            $("#submitForm").attr('action','index.php?p=admin&c=Goods&a=editGoods');
             //$(".none").css("display",'block');
            bindEditData(goods_id);
        }else{
            $("#name").val(" ");
            $("#des").val(" ");
            $("#price").val(" ");
            $("#inventory").val(" ");
            //$("#qrpwd").val(" ");
            $(".store-img-read").attr("src"," ");
            //$(".none").css("display",'none');
            $("#modelTitle").html('新增商品');
            $("#goods_id").val(0);
            $("#submitForm").attr('action','index.php?p=admin&c=Goods&a=addGoods');
        }
        $("#Modal").modal('show');

    }
  
    //获取会员详情单条数据
    function bindEditData(goods_id){

       $.ajax({
           url:'index.php?p=admin&c=Goods&a=getOne',

           data:{goods_id:goods_id},

           type:'post',

            success:function (data) {
                var data = JSON.parse(data);
                $("#name").val(data.name);
                $("#des").val(data.des);
                $("#price").val(data.price);
                $("#inventory").val(data.inventory);
                $(".store-img-read").attr("src",data.photo);
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




