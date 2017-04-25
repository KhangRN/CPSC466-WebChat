$(document).ready(function(){
    
    if(selectedUser != -1){
        $("#friend-"+selectedUser).addClass("selected-friend");
        var friendName = $("#friend-"+selectedUser).text().trim();
        $(".messages-target").empty();
        $(".messages-target").append('<div>Messages with ' + friendName + '</div>');
        $(".messages-target").addClass("set");
        
        //$('#messages-list').animate({scrollTop: $('#messages-list').height()}, 'slow');
        $('#messages-list').animate({scrollTop: 2500}, 'slow');
        
    }
    
    $(".friend-card").click(function(event){
        
        var userId = event.target.id;
        
        if(userId != "friend-" + selectedUser){
            
            $("#"+event.target.id).addClass("selected-friend");
            
            if(selectedUser != -1){
                $("#friend-"+selectedUser).removeClass("selected-friend");
            }
            
            var split = userId.split("-");
            userId = split[1];
            
            selectedUser = userId;
            

            var selector = "#" + event.target.id;
            var friendName = $(selector).text().trim();
            $("#messages-list").empty();
            $("#messages-list").append("<div class='messages-target'></div>")
            $(".messages-target").empty();
            $(".messages-target").append('<div>Messages with ' + friendName + '</div>');
            $(".messages-target").addClass("set");
            $.ajax(
            {
                method: "GET",
                url: "http://localhost:8000/api/users/"+userid+"/messages/"+selectedUser, 
                success: function(result){
                    result.forEach(function(element){
                        //you sent
                        if(element.type == 1){
                            $("#messages-list").append('<div class="message-item"><div class="message-to">' + element.label  + '</div><div class="message-content-to">' + element.content + '</div></div>');
                        }
                        //you receieved
                        else{
                            $("#messages-list").append('<div class="message-item"><div class="message-from">' + element.label  + '</div><div class="message-content-from">' + element.content + '</div></div>');
                        }
                        //$('#messages-list').animate({scrollTop: $('#messages-list').height()}, 'slow');
                        $('#messages-list').animate({scrollTop: 2500}, 'slow');
                    });
                },
                error: function(err){
                    console.log(err)
                }
            });
        }
    });
});

function signOut(url){
    window.location = url;
}

function addFriend(apiUrl){
    var email = $("#add-friend-email").val();
    $("#add-friend-email").val("");
    $.ajax(
    {
        method: "POST",
        data: {email: email},
        url: apiUrl, 
        success: function(result){
            console.log(result)
            console.log(result.code);
            if(result.code == 404){
                $('#alerts').append(
                '<div class="alert alert-danger">' +
                    '<button type="button" class="close" data-dismiss="alert">' +
                    '&times;</button> Could not find a user with that email. </div>');
            }
            else if(result.code == 203){
                $('#alerts').append(
                '<div class="alert alert-warning">' +
                    '<button type="button" class="close" data-dismiss="alert">' +
                    '&times;</button> Already friends with ' + result.firstName + '</div>');
            }
            else if(result.code == 200){
                $('#alerts').append(
                '<div class="alert alert-success">' +
                    '<button type="button" class="close" data-dismiss="alert">' +
                    '&times;</button> Now friends with ' + result.firstName + ' ' + result.lastName +  '</div>');
                
                location.reload();
            }
        },
        error: function(err){
            console.log(err)
        }
    });
}

var socket = io.connect('http://localhost:8080');

socket.emit('user-connect', userid);

socket.on('user-poked', function(name){
    console.log(name + " poked you!");
});

socket.on('message-event', function(from, msg){
    /*$.ajax({
        method: "GET",
        url: "http://localhost:8000/api/users/"+from,
        success: function(result){
            
            
            
        },
        error: function(err){
            console.log(err);
        }
    });*/
    
    //first message
    if($("#no-messages")[0]){
        $("#no-messages").remove();
    }
    
    var selector = "#friend-" + from;
    var friendName = $(selector).text().trim();

    $("#friend-"+selectedUser).removeClass("selected-friend");
    $("#friend-"+from).addClass("selected-friend");
    
    if(from != selectedUser){
        $("#messages-list").empty();
        $("#messages-list").append("<div class='messages-target'></div>")
        $.ajax(
            {
                method: "GET",
                url: "http://localhost:8000/api/users/"+userid+"/messages/"+from, 
                success: function(result){
                    result.forEach(function(element){
                        //you sent
                        if(element.type == 1){
                            $("#messages-list").append('<div class="message-item"><div class="message-to">' + element.label  + '</div><div class="message-content-to">' + element.content + '</div></div>');
                        }
                        //you receieved
                        else{
                            $("#messages-list").append('<div class="message-item"><div class="message-from">' + element.label  + '</div><div class="message-content-from">' + element.content + '</div></div>');
                        }
                        
                        selectedUser = from;
                        var selector = "#friend-" + from;
                        var friendName = $(selector).text().trim();                        
                    });
                    $(".messages-target").empty();
                    $(".messages-target").append('<div>Messages with ' + friendName + '</div>');
                    $(".messages-target").addClass("set");
                    $('#messages-list').animate({scrollTop: 2500}, 'slow');
                },
                error: function(err){
                    console.log(err)
                }
            });
    }
    else{
        $("#messages-list").append(
        '<div class="message-item"><div class="message-from">' + friendName  + '</div><div class="message-content-from">' + msg + '</div></div>'
        );
        //$('#messages-list').animate({scrollTop: $('#messages-list').height()}, 'slow');
        $('#messages-list').animate({scrollTop: 2500}, 'slow');
        $(".messages-target").empty();
        $(".messages-target").append('<div>Messages with ' + friendName + '</div>');
        $(".messages-target").addClass("set");
    }
})

function poke(uid){
    socket.emit('poke', first, uid);
}

function sendMessage(){
    var messageContent = $("#msg-content").val();
    
    if(messageContent.length == 0){
        $('#alerts').append(
                '<div class="alert alert-warning">' +
                    '<button type="button" class="close" data-dismiss="alert">' +
                    '&times;</button>Please enter a message.</div>');
    }
    else if(selectedUser == -1){
        $('#alerts').append(
                '<div class="alert alert-warning">' +
                    '<button type="button" class="close" data-dismiss="alert">' +
                    '&times;</button> Please select a user to send to</div>');
    }
    else{
        $.ajax({
            method: "POST",
            url: "http://localhost:8000/api/messages",
            data: {
              from_id: userid,
              to_id: selectedUser,
              content: messageContent
            },
            success: function(result){
                console.log(result);
            },
            error: function(err){
                console.log(err);
            }
        });
        $("#msg-content").val("");
        $("#alerts").empty();
        socket.emit("send-message", {from: userid, to: selectedUser, content: messageContent});
        
        var selector = "#friend-"+selectedUser;
        var friendName = $(selector).text().trim();
        
        $("#messages-list").append(
                '<div class="message-item"><div class="message-to">You</div><div class="message-content-to">' + messageContent + '</div></div>'
        );
        //$('#messages-list').animate({scrollTop: $('#messages-list').height()}, 'slow');
        //$('#messages-list').animate({scrollTop: $(window).height()}, 'slow');
        $('#messages-list').animate({scrollTop: 2500}, 'slow');
    }
}

function getFriends(){
    $.ajax({
        method: "GET",
        url: "http://localhost:8000/api/friends",
        success: function(result){
            console.log(result);
        },
        error: function(err){
            console.log(err);
        }
    });
}

$(document).keypress(function(e) {
    if(e.which == 13) {
        sendMessage();
    }
});