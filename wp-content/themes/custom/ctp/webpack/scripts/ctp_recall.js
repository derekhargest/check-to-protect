var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
		var response = this.responseText;
		document.getElementById("ctp_recall").innerHTML = '<a style="float: right; border-radius: 50%; padding: 10px 12px; background-color:#000; color: #fff; font-weight:bold; margin-right: 2px" onClick="ctp_close()" href="#">X</a><a onClick="ctp_track()" href="sms://99724?&body='+response+'"><img   src="/wp-content/uploads/2022/03/ctp_mobile_snippet.webp" alt="Recall Concierge" style="width:100%"></a>';
	}
};
xhttp.open("POST", "https://checktoprotect.me/scripts/phpajax/ctp_sms_message.php", true);
xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhttp.send("id=41697");
function ctp_track(){
	var xhttp = new XMLHttpRequest();
	xhttp.open("POST", "https://checktoprotect.me/ajax_log_user.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send("type=120&dealer_id=207");
}
function ctp_close(){
	document.getElementById("ctp_recall").innerHTML = '';
}
