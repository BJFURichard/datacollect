var sContent1 = "<h4 class='text-success' style='margin:0 0 5px 0;padding:0.2em 0'>姓名：" 
var sContent2 = "</h4>";
var sContent3="<p >到达时间<a class='text-error'>";
var sContent4 = "</a></p>";

var picCon1 = "";
var txtCon1 = "";


window.openInfoWinFuns = null;
openInfoWinFuns=[];

var pointemp = [];
var opts = {
width : 400,     // 信息窗口宽度
height: 150,     // 信息窗口高度
// 信息窗口标题
enableMessage:true,//设置允许信息窗发送短息
}
//var marker = [];
//var infoWindow = [];
var map	 = new BMap.Map("mapcontainer",{mapType: BMAP_HYBRID_MAP});  // 创建地图实例
var point = new BMap.Point(116.099337,40.068985);  
map.centerAndZoom(point, 13); 
map.enableScrollWheelZoom(); // 允许滚轮缩放
map.addControl(new BMap.NavigationControl());

function addLayerToMap(data){
	var s = [];
	
	for(i=0;i<data.length;i++)
	{
		/*添加地图控件*/
		var point= new BMap.Point(data[i].len, data[i].lat);
		var marker = addMarker(point,i);
		var openInfoWinFun =addInfoWindow(marker,data[i],i,1);
		openInfoWinFuns.push(openInfoWinFun);
		/*地图控件添加完毕--添加html*/
		var selected = "";
		if(i == 0){
			selected = "background-color:#f0f0f0;";
			openInfoWinFun();
		}
		s.push('<li id="list' + i + '" style="margin: 2px 0pt; padding: 0pt 5px 0pt 3px; cursor: pointer; overflow: hidden; line-height: 17px;' + selected + '" onclick="openInfoWinFuns[' + i + ']()">');
		s.push('<span style="width:1px;background:url(http://developer.baidu.com/map/jsdemo/demo/red_labels.gif) 0 ' + ( 2 - i*20 ) + 'px no-repeat;padding-left:10px;margin-right:3px"> </span>');
        s.push(data[i].name+ '</li>');
        s.push('');
	}
	
	
	$('#userList').html(s.join(""));
}
function addLayerToMap2(data){
	var s = [];
	var p=[];
	var tmpname=data[0].name;
	var count=0;
	//初始化
	s.push('<li id="list' + 0 + '" style="margin: 2px 0pt; padding: 0pt 5px 0pt 3px; cursor: pointer; overflow: hidden; line-height: 17px;' + selected + '" onclick="lines('+data[0].id+')">');
	s.push('<span style="width:1px;background:url(http://developer.baidu.com/map/jsdemo/demo/red_labels.gif) 0 ' + ( 2 - count*20 ) + 'px no-repeat;padding-left:10px;margin-right:3px"> </span>');
	s.push(data[0].name+ '</li>');
	s.push('');
	//开始循环
	for(i=0;i<data.length;i++)
	{
		/*添加地图控件*/
		/*var point= new BMap.Point(data[i].len, data[i].lat);
		var marker = addMarker(point,i);
		var openInfoWinFun =addInfoWindow(marker,data[i],i,1);
		openInfoWinFuns.push(openInfoWinFun);
		
		var selected = "";
		if(i == 0){
			selected = "background-color:#f0f0f0;";
			openInfoWinFun();
		}
		if(tmpname==data[i].name){
			p.push(point);
		}else{
			//lines.push(addLine(point));
			//
			var polyline = new BMap.Polyline(p,{strokeColor:"blue", strokeWeight:6, strokeOpacity:0});    
			map.addOverlay(polyline); 
			
			//s.push('<li id="list' + i + '" style="margin: 2px 0pt; padding: 0pt 5px 0pt 3px; cursor: pointer; overflow: hidden; line-height: 17px;' + selected + '" onclick="lines[' + count + ']()">');
			//s.push('<span style="width:1px;background:url(http://developer.baidu.com/map/jsdemo/demo/red_labels.gif) 0 ' + ( 2 - count*20 ) + 'px no-repeat;padding-left:10px;margin-right:3px"> </span>');
			//s.push(data[i].name+ '</li>');
			//s.push('');
			tmpname=data[i].name;
			count+=1;
			
			point=[];
		}*/
		var selected = "";
		
		if(tmpname==data[i].name){
			var point= new BMap.Point(data[i].len, data[i].lat);
			var marker = addMarker(point,count);
			var openInfoWinFun =addInfoWindow(marker,data[i],i,2);
			pointemp.push(point);
			
		}else{
			var point= new BMap.Point(data[i].len, data[i].lat);
			var marker = addMarker(point,++count);
			var openInfoWinFun =addInfoWindow(marker,data[i],i,2);
			var polyline = new BMap.Polyline(pointemp,{strokeColor:"red", strokeWeight:6, strokeOpacity:0});    
			map.addOverlay(polyline); 
			/*window.setTimeout(function(){
			  map.panTo(pointemp[0]);
			}, 2000);*/
			pointemp.length=0;
			pointemp.push(point);
			tmpname=data[i].name;
			s.push('<li id="list' + i + '" style="margin: 2px 0pt; padding: 0pt 5px 0pt 3px; cursor: pointer; overflow: hidden; line-height: 17px;' + selected + '" onclick="lines('+data[i].id+')" onmousemove="over()">');
			s.push('<span style="width:1px;background:url(http://developer.baidu.com/map/jsdemo/demo/red_labels.gif) 0 ' + ( 2 - count*20 ) + 'px no-repeat;padding-left:10px;margin-right:3px"> </span>');
			s.push(data[i].name+ '</li>');
			s.push('');
			
		}
	}
	var polyline = new BMap.Polyline(pointemp,{strokeColor:changeColor(), strokeWeight:6, strokeOpacity:1});    
	map.addOverlay(polyline); 
	window.setTimeout(function(){
		map.panTo(pointemp[0]);
	}, 2000);
	$('#userList').html(s.join(""));
}
function lines(id ){
	
	var dd = new Date(); 
	dd.setDate(dd.getDate()+0);//获取AddDayCount天后的日期 
	var y = dd.getFullYear(); 
	var m = dd.getMonth()+1;//获取当前月份的日期 
	var d = dd.getDate(); 
	if (m >= 1 && m <= 9) {
        m = "0" + m;
    }
	if (d >= 1 && d<= 9) {
        d = "0" + d;
    }
	var day =y+"-"+m+"-"+d;
	a(id,y+"-"+m+"-"+d);
}
function changeColor(){
   var r = parseInt(Math.random() * 255);
   var g = parseInt(Math.random() * 255);
   var b = parseInt(Math.random() * 255);
   var colorHex = r.toString(16) + g.toString(16) + b.toString(16);
   var bgColor = "#"+colorHex;
   return bgColor;
}
function addLine(point)
{
	//map.clearOverlays();
	var line=function(){
		var polyline = new BMap.Polyline(point,{strokeColor:"blue", strokeWeight:6, strokeOpacity:0.5});    
		map.addOverlay(polyline);
		window.setTimeout(function(){
			map.panTo(point[0]);
		}, 2000);
	}
	return line;
}

function addMarker(point,i){
	var myIcon = new BMap.Icon("http://api.map.baidu.com/img/markers.png", new BMap.Size(23, 25), {
		offset: new BMap.Size(10, 25),
		imageOffset: new BMap.Size(0, 0 - i * 25)
	  });
	var marker = new BMap.Marker(point, {icon: myIcon});
	map.addOverlay(marker);
	return marker;
}
function addInfoWindow(marker,inf,i,incase){
    var maxLen=10;
	var infoWindow = new BMap.InfoWindow(sContent1+inf.name+sContent2+picCon1+sContent3+inf.time+sContent4+txtCon1+"</div>",opts);	
    var openInfoWinFun = function(){
        marker.openInfoWindow(infoWindow);
		if(incase==1){
			for(var cnt = 0; cnt < maxLen; cnt++){
				if(!document.getElementById("list" + cnt)){continue;}
				if(cnt == i){
					document.getElementById("list" + cnt).style.backgroundColor = "#f0f0f0";
				}else{
					document.getElementById("list" + cnt).style.backgroundColor = "#fff";
				}
			}
		}
    }
    marker.addEventListener("click", openInfoWinFun);
    return openInfoWinFun;
}
function a(id,day){
	
	$.post("./getMarksByID", 
			{
				user: id,
				date: day,
			},
			function(data){
			console.log(data);
				var sdata = [{}]; //初始化对象数组
				var j=0;
				map.clearOverlays(); 
				var pointemp=[];
				for(i=0;i<data.length;i++)
				{
					var point= new BMap.Point(data[i].len, data[i].lat);
					pointemp.push(point);
					var marker = addMarker(point,i);
					var openInfoWinFun =addInfoWindow(marker,data[i],i,2);
					var polyline = new BMap.Polyline(pointemp,    {strokeColor:"blue", strokeWeight:6, strokeOpacity:0});    
					map.addOverlay(polyline); 
					window.setTimeout(function(){
					  map.panTo(pointemp[0]);
					}, 2000);
					map.centerAndZoom(pointemp[0], 22); 
					
				}
				
			},
            "json"
		);
}
function addDateToList(data){
	var s=[];
	for(i=0;i<data.length;i++)
	{
		var selected = "";
		if(i == 0){
			selected = "";
		}
		s.push('<li id="list' + i + '" style="margin: 2px 0pt; padding: 0pt 5px 0pt 3px; cursor: pointer; overflow: hidden; line-height: 17px;' + selected + '" onclick="a('+data[i].id+',\''+data[i].day+'\')" onmousemove="this.style.cssText=\'background-color:#f0f0f0;margin: 2px 0pt; padding: 0pt 5px 0pt 3px; cursor: pointer; overflow: hidden; line-height: 17px;\'" onmouseout="this.style.cssText=\'background-color:#fff;margin: 2px 0pt; padding: 0pt 5px 0pt 3px; cursor: pointer; overflow: hidden; line-height: 17px;\'">');
		s.push('<span style="width:1px;background:url(http://developer.baidu.com/map/jsdemo/demo/red_labels.gif) 0 ' + ( 2 - i*20 ) + 'px no-repeat;padding-left:10px;margin-right:3px"> </span>');
        s.push(data[i].day+ '</li>');
        s.push('');
	}
	$('#userList').html(s.join(""));
}

function over(){
this.style.backgroundColor="#f0f0f0";
}