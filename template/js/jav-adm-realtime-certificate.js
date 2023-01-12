$(document).ready(function(){

	function sendingCertificate(){
		var formData = new FormData();
		var sentBy = $("#admin").text();
        formData.append("send-yearly-certificate", sentBy);
        formData.append("totalCertificateSend", 5);
        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            enctype: "multipart/form-data",
            url: "/handler/handler_ajax_course.php",
            data: formData,
            success: function (data) {
                var result = $.parseJSON(data);
                if(result.info != "success"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");
                }else{       
                    
                }
            },error: function(){
                alert("error in ajax form submission");
            }
        });
	}

	function reloadProgressbar(){
		var formData = new FormData();
        formData.append("getTotalYearlyCertificateSent", "");
        
        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            enctype: "multipart/form-data",
            url: "/handler/handler_ajax_course.php",
            data: formData,
            success: function (data) {
                var result = $.parseJSON(data);
                if(result.info != "success"){
                    $("#failedMessage").html(result.info);
                    $("#failed").modal("show");
                }else{
                    $("#certificate-progress-bar").attr('aria-valuenow', result.percent).css('width', result.percent+"%");
                    $("#certificate-progress-bar").html("<span class=\'sr-only\'>" + result.percent + "% Complete (success)</span>" + result.percent + "%");
                    $("#progress-bar-detail").html("Completed " + result.certificateSent + " of " + result.certificateTotal);
                }
            },error: function(){
                alert("error in ajax form submission");
            }
        });
		
	}

	setInterval(function(){
		reloadProgressbar();
    }, 2000);
	var currentTime = new Date()
	var currentDay = currentTime.getDate();
	var currentMonth = currentTime.getMonth() + 1;
	var currentYear = currentTime.getFullYear();


	var dateFrom = "01/12/" + currentTime.getFullYear();
	var dateTo = "31/12/" + currentTime.getFullYear(); 
	var dateCheck = currentDay + "/" + currentMonth + "/" + currentYear;
	// var dateCheck = "2/12/2021";

	var d1 = dateFrom.split("/");
	var d2 = dateTo.split("/");
	var c = dateCheck.split("/");

	var from = new Date(d1[2], parseInt(d1[1])-1, d1[0]);  // -1 because months are from 0 to 11
	var to   = new Date(d2[2], parseInt(d2[1])-1, d2[0]);
	var check = new Date(c[2], parseInt(c[1])-1, c[0]);

	if(check > from && check < to){
		console.log("sending certificate...");
		setInterval(function(){
			sendingCertificate();
    	}, 2000);
	}else{
		console.log("not the time");
	}

	
    

});