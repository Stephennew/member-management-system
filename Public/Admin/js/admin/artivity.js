/**
 * Created by lxh on 2018/03/13.
 */

$(function () {

    $("#submitForm").validate();
    var tableUrl="index.php?p=admin&c=Artivity&a=getList";

    window.operateEvents = {
        'click #artivity_edit': function (e, value, row, index) {

            openModel(row.artivity_id);
        },
        'click #artivity_delete': function (e, value, row, index) {
            parent.layer.confirm('确认删除？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                artivityDelete(row.artivity_id,'index.php?p=admin&c=Artivity&a=del');
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
                title:$('#Keyword').val(),
                des:$('#Keywords').val(),
                sortName: params.sortName,
                sortOrder: params.sortOrder,
                offset: params.offset,
            };
            return temp;
        },
    
        columns:[
            {
                field:'artivity_id',
                title:'行号',
            },
            {
                field:'title',
                title:'活动标题',
            },
            {
                field:'des',
                title:'活动简介',
            },
            {
                field:'time',
                title:'发布时间',

            },
            {
                field:'start',
                title:'开始日期',

            },

            {
                field: 'end',
                title: '结束日期',


            },
            {
                field:'operate',
                title:'操作',
                events:operateEvents,
                formatter:function (index,row) {
                    var e = '<a href="#" id="artivity_edit" class="btn btn-primary btn-sm">修改</a> ';
                    var s= '<a href="#" id="artivity_delete" class="btn btn-danger btn-sm">删除</a> ';
                    return e+s;
                }
            }
        ],
    });


    //打开会员详情model


     function openModel(artivity_id){

        $("#alert").hide();

        if(artivity_id>0){
            $("#modelTitle").html('修改活动');
            $("#artivity_id").val(artivity_id);
            $("#submitForm").attr('action','index.php?p=admin&c=Artivity&a=editArtivity');
            bindEditData(artivity_id);
        }else{
            $("#title").val(" ");
            $("#des").val(" ");
            $("#start").val(" ");
            $("#end").val(" ");
            $("#editor").val(" ");
            $("#modelTitle").html('添加活动');
            $("#artivity_id").val(0);
             $("#submitForm").attr('action','index.php?p=admin&c=Artivity&a=addArtivity');
        }

        $("#Modal").modal('show');

    }
  
    //获取会员详情单条数据
    function bindEditData(artivity_id){

       $.ajax({
           url:'index.php?p=admin&c=Artivity&a=getOne',

           data:{artivity_id:artivity_id},

           type:'post',

            success:function (data) {
                var data = JSON.parse(data);
                console.log(data);
                $("#title").val(data.title);
                $("#des").val(data.des);
                $("#content").val(data.content);
               /* $("#start").val(data.start);
                $("#end").val(data.end);*/
                $("#start").attr('value',data.start);
                $("#end").attr('value',data.end);

               
            }
       });
    }
    function artivityDelete(artivity_id,url){
        $.ajax({
            type:'post',
            url:url,
            data: {artivity_id:artivity_id},
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




