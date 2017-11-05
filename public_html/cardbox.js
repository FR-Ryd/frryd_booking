$(document).ready(function () {
    var queueRequest;
    var sendRequest;
    var requestDelay = 1000;
    var funcs = {};

    var previousCard = "";

    funcs.queueRequest = function() {
        setTimeout(funcs.sendRequest, requestDelay);
    };

    funcs.success = function(data){ //callback function
        newCard = data.cardId;
        if(newCard == previousCard) return;

        previousCard = newCard;

        $("#CardBoxCard").val(newCard);
        $("#CardBoxName").val("");
        $("#CardBoxName").prop("readonly", true);
        $("#CardBoxName").click(function(){});
        if(data.liuId) {
            funcs.displayUser(data);
        } else {
            funcs.displayRegister(data);
        }
    };

    funcs.sendRequest = function() {
        $.ajax({
            method: "GET",
            url: "/cardbox.php",
            dataType: "json", //jquery will convert the json into an object
            cache: false,
            success: funcs.success,
            complete: funcs.queueRequest,
        });
    }

    funcs.displayUser = function(data) {
        $("#CardBoxLiuId").val(data.liuId);
        $("#CardBoxLiuId").attr('disabled', 'disabled');

        $("#CardBoxLiuGet").attr('disabled', 'disabled');
        $("#CardBoxLiuRegister").attr('disabled', 'disabled'); //Enabled on successful get
        $("#CardBoxLiuNew").attr('disabled', 'disabled'); //Will create user & go to user page

        $("#CardBoxName").val(data.name);
        $("#CardBoxName").prop("readonly", false);
        $("#CardBoxName").click(function(){
            document.location = "user.php?showUser=" + data.liuId;
        });
    };

    funcs.displayRegister = function(data) {
        $("#CardBoxLiuId").val("");
        $("#CardBoxLiuId").attr('disabled', false);

        $("#CardBoxLiuGet").attr('disabled', false);
        //$("#CardBoxLiuRegister").hide(); //Enabled on successful get
        //$("#CardBoxLiuNew").show(); //Will create user & go to user page

        //$("#CardBoxName").val("");
        //$("#CardBoxName").show();
        //$("#CardBoxName").attr('disabled', false);
    };

    $("#CardBoxLiuGet").click(function() {
        //Send request for data for user with liu-id
        var liuId = $("#CardBoxLiuId").val();
        if(liuId == "") return;

        $.ajax({
            method: "GET",
            url: "/cardbox.php",
            dataType: "json", //jquery will convert the json into an object
            cache: false,
            data: { //data sent to server
                cardId: previousCard,
                liuId: liuId,
                lookup: true,
            },
            success: function(data) {
                if(data.noSuchUser) {
                    $("#CardBoxLiuNew").attr('disabled', false);
                    $("#CardBoxLiuRegister").attr('disabled', 'disabled');
                    $("#CardBoxName").val("");
                } else {
                    $("#CardBoxName").val(data.name);
                    $("#CardBoxLiuRegister").attr('disabled', false);
                    //$("#CardBoxLiuRegister").show();
                    $("#CardBoxLiuNew").attr('disabled', 'disabled');
                }
            },
        });

    });
    $("#CardBoxLiuRegister").click(function() {
        //Need to have propert liu id and name and exist. Will make the binding between existing user and card.
        //If successfull, will go to users page.
        var liuId = $("#CardBoxLiuId").val();
        document.location="/cardbox.php?register&card=" + previousCard + "&liuId="+liuId;
    });

    $("#CardBoxLiuNew").click(function() {
        //Enabled if no user exist with this liuid.
        //Use the supplied name, liu-id & card number to create user.
        //On success go to his page.
        var liuId = $("#CardBoxLiuId").val();
        //var name = $("#CardBoxName").val();
        document.location = "/cardbox.php?new&card=" + previousCard + "&liuId="+liuId + "&name=" + name;
    });
    
    funcs.sendRequest();
});
