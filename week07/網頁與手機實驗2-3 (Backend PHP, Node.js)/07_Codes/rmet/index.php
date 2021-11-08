<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<link rel="stylesheet" href="css/jquery.mobile-1.4.5.min.css">
<script src="js/jquery-1.11.3.min.js"></script>
<script src="js/jquery.mobile-1.4.5.min.js"></script>
<script src="jquery.touchSwipe.min.js"></script>

<br><br><br><br>
<center>
UP<br>
LEFT
<img id=pic style="position:relative;vertical-align:middle" width="50%" src="images/<?= empty($_GET['i']) ? 1 : $_GET['i'] ?>.jpg">
RIGHT<br>
DOWN<br>
</center>

<script>

var Idx=<?= empty($_GET['i']) ? 1 : $_GET['i'] ?>;
var MaxIdx=3;
function nextPage(){
 if(Idx<MaxIdx){ 
  Idx++;
 }else{
  Idx=1;
 }
 window.location.replace("index.php?i="+Idx);
}

$(function() {      
 $(document).swipe({
  swipe:function(event, direction, distance, duration, fingerCount, fingerData){
   switch(direction){
    case "left":
     $("#pic").animate({right:"100px"}).fadeOut("slow",nextPage);
     break;
    case "right":
     $("#pic").animate({left:"100px"}).fadeOut("slow",nextPage);
     break;
    case "up":
     $("#pic").animate({bottom:"100px"}).fadeOut("slow",nextPage);
     break;
    case "down":
     $("#pic").animate({top:"100px"}).fadeOut("slow",nextPage);
     break;
   }
  },threshold:0
 });
});
</script>
