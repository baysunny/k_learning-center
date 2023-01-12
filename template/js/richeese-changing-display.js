$(document).ready(function(){
    function noPreview(imageBefore) {
        $("#edit-account-image").attr("src", imageBefore);
    }
	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $("#edit-account-image").attr("src", e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#input-user-image").change(function() {
        var file = this.files[0];
        var currentImage = $("#edit-account-image").attr("src");
        if(typeof(file) !== "undefined"){
            var extension = file.name.split(".").pop().toLowerCase();
            if(jQuery.inArray(extension, ["png", "jpg", "jpeg"]) === -1){
                $("#button-user-image").removeClass("btn-info");
                $("#button-user-image").addClass("btn-outline-info");
                noPreview(currentImage);
            }else{
                $("#button-user-image").removeClass("btn-outline-info");
                $("#button-user-image").addClass("btn-info");
                readURL(this);
            }
        }else{
            $("#button-user-image").removeClass("btn-info");
            $("#button-user-image").addClass("btn-outline-info");
            noPreview(currentImage);
        }
    });

    function createTable(tableData) {
        if(tableData.length < 1){
            return "<h3>Kosong</h3>";
        }
        var result = "<table id=\'demo-datatables-scroller-2\' class=\'table table-striped table-nowrap dataTable\' cellspacing=\'0\' width=\'100%\'><thead><tr><th>No</th><th>Judul</th><th>Waktu(detik)</th><th>Time</th></tr></thead><tbody>";
        var n = 0;
        for(var i=0; i<tableData.length; i++) {
            n++;
            result += "<tr>";
            result += "<td class=\'text-left\'>"+n+"</td>";
            result += "<td class=\'text-left\'><a href=\'/dashboard/materi/read.php?subject=" + tableData[i].subjectID +"\'><span class=\'icon icon-book\'></span>" + tableData[i].subjectName+ "</a></td>";
            result += "<td class=\'text-left\'>"+tableData[i].timeInSecond+"</td>";
            result += "<td class=\'text-left\'>"+tableData[i].timeRead+"</td>";
            result += "</tr>";
        }
        result += "</tbody></table>";
        return result;
    }

    function createGenderDropDown(gender){
        var result = "";
        if("Laki-Laki".localeCompare(gender) == 0 || "1".localeCompare(gender) == 0){
            result = "<option value=\'Laki-Laki\' selected>Laki-Laki</option><option value=\'Perempuan\'>Perempuan</option>";
        }else{
            result = "<option value=\'Laki-Laki\'>Laki-Laki</option><option value=\'Perempuan\' selected>Perempuan</option>";
        }return result;
    }

    function createMonthDropDown(month){
        var months = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];

        var result = "";
        var n;
        var mon;
        for(var i=0; i<months.length; i++){
            n = i + 1;
            
            if(n < 10){
                mon = "0" + n;
            }else{
                mon = n;
            }
            if(month == n){
                result += "<option value=" + mon + " selected>"+months[i]+"</option>";
            }else{
                result += "<option value=" + mon + ">"+months[i]+"</option>";
            }
        }return result;
    }


});