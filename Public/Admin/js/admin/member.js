/**
 * Created by lxh on 2018/03/13.
 */

$(function () {

    $("#submitForm").validate();
    $("#rechargeForm").validate();
    var tableUrl="index.php?p=admin&c=Member&a=getList";

    window.operateEvents = {
        'click #member_edit': function (e, value, row, index) {

            openModel(row.member_id);
        },

        'click #member_delete': function (e, value, row, index) {
            parent.layer.confirm('确认要删除该会员？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                memberDelete(row.member_id ,'index.php?p=admin&c=Member&a=del');
            }, function(){
            });
        },

        'click #member_recharge': function (e, value, row, index) {
            openRechargeModel(row.member_id);
        },
        'click #member_expend': function (e, value, row, index) {
            openExpendModel(row.member_id);
        },

    }

    function openRechargeModel(member_id){

        $("#recharge-alert").hide();
        $("#id").val(member_id);
        $("#recharge-Modal").modal('show');
    }

    function openExpendModel(member_id){

        $("#expend-alert").hide();
        $("#expends_member_id").val(member_id);
        $("#expend-Modal").modal('show');
    }
    $("#expendBtn").click(function () {

        var flag = $("#rechargeForm").valid();

        if(!flag){
            //没有通过验证
            console.log('is run');
            return;
        }
        $.ajax({type:'post',url:$("#expendForm").attr('action'),data: $("#expendForm").serialize(),

            success:function (data) {
                var data = JSON.parse(data);
                if(data.status==1){

                    $("#recharge-Modal").modal('hide');

                    window.location.reload();
                }else{

                    $("#alert-message").html(data.message);

                    $("#recharge-alert").show();

                }
            }
        });
    });

    $("#Rechargebtn").click(function () {

        var flag = $("#rechargeForm").valid();

        if(!flag){
            //没有通过验证
            console.log('is run');
            return;
        }
        $.ajax({type:'post',url:$("#rechargeForm").attr('action'),data: $("#rechargeForm").serialize(),

            success:function (data) {

                if(data){

                    $("#recharge-Modal").modal('hide');

                    window.location.reload();
                }else{

                    $("#alert-message").html(data.message);

                    $("#recharge-alert").show();

                }
            }
        });
    });

    function  memberDelete(member_id,url){
        $.ajax({
            type:'post',
            url:url,
            data: {member_id:member_id},
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
                level_id:$('#member_level').val(),
                sex:$('#member_sex').val(),
                keyword:$('#keyword').val(),
                sortName: params.sortName,
                sortOrder: params.sortOrder,
                offset: params.offset,
            };
            return temp;
        },

        columns:[
            {
                field:'member_id',
                title:'行号',
            },

            {
                field:'photo',
                title:'头像',

                formatter:function (index,row) {
                    var e;
                    e='<img src='+row.photo+' width="50px" >'
                    return e;
                }
            },

            {
                field:'user_name',
                title:'会员名',
            },
            {
                field:'realname',
                title:'真实姓名',
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
                field:'level_name',
                title:'VIP等级',
            },
            {
                field:'telphone',
                title:'手机号码',
            },
            {
                field:'balance',
                title:'余额',
            },
            {
                field:'points',
                title:'积分',
            },

            {
                field:'operate',
                title:'操作',
                events:operateEvents,
                formatter:function (index,row) {
                    var e = '<a href="#" id="member_edit" class="btn btn-primary btn-sm">查看</a> ';
                    var h= '<a href="#" id="member_recharge" class="btn btn-success btn-sm">充值</a> ';
                    var d= '<a href="#" id="member_expend" class="btn btn-success btn-sm">消费</a> ';
                    var s= '<a href="#" id="member_delete" class="btn btn-danger btn-sm">删除</a> ';
                    return e+h+d+s;
                }
            }
        ],
    });


    //打开会员详情model


    function openModel(member_id){

        $("#alert").hide();

        if(member_id>0){
            $("#modelTitle").html('会员修改');
            $("#member_id").val(member_id);
            $("#submitForm").attr('action','index.php?p=admin&c=Member&a=editMember');
            $(".none").css("display",'none');
            $('.addnone').css("display","block");
            bindEditData(member_id);
        }else{
            $("#user_name").val(" ");
            $("#password").val(" ");
            $("#qrpwd").val(" ");
            $("#realname").val(" ");
            $("#telphone").val(" ");
            $("#remarks").val(" ");
            $(".store-img-read").attr("src"," ");
            $("#modelTitle").html('会员添加');
            $("#member_id").val(0);
            $(".none").css("display",'block');
            $('.addnone').css("display","none");
            $("#submitForm").attr('action','index.php?p=admin&c=Member&a=addMember');
        }

        $("#Modal").modal('show');

    }

    //获取会员详情单条数据
    function bindEditData(member_id){

        $.ajax({
            url:'index.php?p=admin&c=Member&a=getFindData',

            data:{member_id:member_id},

            type:'post',

            success:function (data) {
                var data = JSON.parse(data);
                $("#user_name").val(data.user_name);
                $("#realname").val(data.realname);
                $("#telphone").val(data.telphone);
                $("#remarks").val(data.remarks);
                $("#level_name").val(data.level_name);
                $(".sexrows input[data-sex='"+data.sex+"']").attr("checked","checked");
                $(".store-img-read").attr("src",data.photo);
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




