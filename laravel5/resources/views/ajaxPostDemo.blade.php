<html>
<head>
    <title>Laravel Ajax Post示例</title>
    {{--<meta name="_token" content="{{ csrf_token() }}"/>--}}

    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <script type="application/javascript">
        function getMessage(){

//            $.ajaxSetup({
//                headers: {
//                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
//                }
//            });

            $.ajax({
                type:'post',
//                url:'ajaxPostUrl',
//                url:'http://221.236.173.192:8888/cors/test',
//                url:'http://221.236.173.192:8888/ajaxPostUrl',
//                url:'http://221.236.173.192:8888/api/admin/distributeRoomRegister',
                url:'api/admin/login',
                data:{'username':"admin", 'password':'admin'},
//                data:{
//                    'serialId':"5",
//                    'roomName':'配电室5',
//                    'description':"miaoshu",
//                    'address':'uestc',
//                    'productionPro':'switch',
//                    'telephoneNumber':'15828251950',
//                    'installationDate':'2015-01-11'
//                },

//                url:'api/content/insertAssetInfo',
//                data:{
//                    'serialId': '111',
//                    'name':'开关2',
//                    'type':"switch",
//                    'unit':'个',
//                    'amount':'4',
//                    'addDate':'2015-01-11'
//                },

//                  url:'api/cloud/deviceRegister',
//                  data:{
//                      'gprsID':'000000001',
//                      'deviceName':'昂思数显1',
//                      'deviceTypeName':'US2000',
//                      'dataBaseName':'jinyehotel',
//                      'roomId':'0',
//                      'protocolVersion':'1',
//                      'protocolRemark':'1',
//                      'mobileNumber':'17088176258',
//                      'longitude':'0.000000',
//                      'latitude':'0.0000000',
//                      'installDirection':'1',
//                      'controllerAddress':'test',
//                      'isDiscarded':'0',
//                      'addDate':'2017-01-11 00:00:00'
//        },
                dataType: 'json',
//                headers: {
//                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
//                },
                success:function(data){
                    $("#msg").html(data.msg);
                },
                error:function(){
                    alert("error...");
                }
            });
        }
    </script>
</head>

<body>
<div id = 'msg'>
    <br/>
    <input type="button" value="POST" onclick="return getMessage()" />

</div>

</body>

</html>