function wpanticopySendCopiedData(doc) {

  var id=(doc.getAttribute("contentid"));
  var copied_content = (document.getSelection());
  
  var url=document.getElementById("cpycntntajxurl").value; 
  var srvr=new XMLHttpRequest();

  srvr.onreadystatechange=function(){
    if(this.readyState==4 && this.status==200)
    {
      
    }
  };
  srvr.open('POST',url,true);

  let form=new FormData(document.createElement("form"));
  form.append("action","wpanticopy_copied");
  form.append("text_id",id);
  form.append("text_copy",copied_content);
  form.append("wpcopycontentcsef",document.getElementById("wpanticopycsrf").value);
  srvr.send(form);
}

function wpcopyContentPrevention(e)
{
	e.preventDefault();
}