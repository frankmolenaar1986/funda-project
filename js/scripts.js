$(document).ready(function(){
    $("button").on("click", function(){
        $.ajax({
            method: "POST",
            url: "php/client.php",
            success: function(data) {
                $("#result-container table tbody").html(data);
            }
        });
    });
});