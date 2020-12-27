$(document).ready(function(){
    setRecentChats();
    
    setInterval(function(){
        getNewChats();
        getOpenedChatReceivedMessages();
    }, 10000);

});


//send message to server, update view
function sendMessage(id, append){
    var messageText=document.getElementById('send-form').message;
    if(messageText.value!=""){
        jQuery.ajax({
            url: "chat/send",
            type: "post",
            data: "id=" + id + "&message="+messageText.value,
            success: function(response){
                if(response!="error"){
                    var message={
                        text: messageText.value,
                        time: response
                    };

                    if(append){
                        appendSentMessage(message); //display new message in message box
                        setToMostRecent(id, message.text, message.time); //display current chat at the beginning of recent
                    }
                    else{
                        setRecentChats();
                    }
                    messageText.value="";
                    
                }
                else{
                    alert(response);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
            
            }
        });
    }

    return false;
}

function getNewChats(){
    jQuery.ajax({
        url: "chat/newUpdates",
        type: "post",
        success: function(response){
            if(response!="no data"){
                var recent= JSON.parse(response);
                recent.forEach(updateRecentChats);
            }
        }
    });
}

function getOpenedChatReceivedMessages(){
    var id=document.getElementById("selectedConversation").innerHTML;
    if(id!=0){
        jQuery.ajax({
            url: "chat/received",
            type: "post",
            data: "id=" + id,
            success: function(response){
                if(response!="no data"){
                    var messages= JSON.parse(response);
                    messages.forEach(appendReceivedMessage);
                }
            }
        });
    }
}

//sets a recent a message at the begin and updates its data
function setToMostRecent(id, message, time){
    if(message.length >100){
        message= message.substring(0,100);
    }
    if($("#"+id) != null){
        var item=$("#"+id);
        item.find("p").text(message);
        item.find("small").text(time);

        $("#recentChats").detach(item);
        $("#recentChats").prepend(item);
    }
}


//append received messages and send messages
function appendMessages(message, index){
    if(message.display=='right'){
        appendSentMessage(message);
    }
    else{
        appendReceivedMessage(message);
    }
}


//append sent message div at the message box
function appendSentMessage(message){
    var p="<p class='text-small mb-0 text-white'>"+message.text+"</p>";
    var pTime="<p class='small text-muted'>"+message.time+"</p>";
    var div1= "<div class='bg-primary rounded py-2 px-3 mb-2'>"+p+"</div>";
    var div2= "<div class='media-body'>" + div1 + pTime + "</div>";
    var div3 = "<div class='media w-50 ml-auto mb-3'>"+div2+"</div>";

    $("#messages-container").append(div3);

    //scroll to bottom of div
    var messagesDiv = document.getElementById("messages-container");
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}


//append received message at the message box
function appendReceivedMessage(message){
    var p="<p class='text-small mb-0 text-muted'>"+message.text+"</p>";
    var pTime="<p class='small text-muted'>"+message.time+"</p>";
    var div1= "<div class='bg-light rounded py-2 px-3 mb-2'>"+p+"</div>";
    var div2= "<div class='media-body ml-3'>" + div1 + pTime + "</div>";
    var div3 = "<div class='media w-50 mb-3'><img src='uploads/images/"+message.picture+"' alt='user' width='50' class='rounded-circle'>"+ div2 +"</div>";

    $("#messages-container").append(div3);

    //scroll to bottom of div
    var messagesDiv = document.getElementById("messages-container");
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}


//shows messages for a selected conversation
function showSelectedChatMessages(id){

    //make selected conversation look blue
    var current=$(".active");
    var newSelected= document.getElementById(id);

    current.removeClass("text-white");
    current.removeClass("active");
    current.addClass("list-group-item-light");
    
    newSelected.classList.remove("list-group-item-light");
    newSelected.classList.add("active");
    newSelected.classList.add("text-white");

    //save current chat id to a hidden element
    document.getElementById("selectedConversation").innerHTML=id;

    //set form onsubmit to send data of current chat
    document.getElementById('send-form').setAttribute("onsubmit", "return sendMessage("+id+", true)");

    displayMessages(id); //retrieve all messages and display them
}

//get conversation messages from the server and display them to chat box
function displayMessages(id){
    document.getElementById("messages-container").innerHTML="";

    jQuery.ajax({
        url: "chat/messages",
        type: "post",
        data: "id=" +id,
        success: function(response){
            if(response!="no data"){
                var messages= JSON.parse(response);
                messages.forEach(appendMessages);
            }
            else{
                //alert(response);
            }
        }
    });
}


//add recent Chats on the sidebar
function setRecentChats(){
    jQuery.ajax({
        url: "chat/recents",
        type: "post",
        success: function(response){
            if(response!="no data"){
                document.getElementById("recentChats").innerHTML="";
                var recent= JSON.parse(response);
                recent.forEach(appendRecentChat);

                document.getElementById("recentChats").children[0].click(); //select most recent chat to display
            }
            else{
                //alert(response);
            }
        }
    });
}

//set to most recent existing chat, or append new one
function updateRecentChats(chat, index){
    if(document.getElementById(chat.id) !=null){
        setToMostRecent(chat.id, chat.message, chat.time);
    }
    else{
        appendRecentChat(chat, index);
    }
}

//add a single chat at the beginning of recents sidebar
function appendRecentChat(chat, index){
    var p = "<p class='font-italic mb-0 text-small'>"+chat.message+"</p>";
    var h6= "<h6 class='mb-0'>"+ chat.firstname + " " + chat.lastname +"</h6><small class='small font-weight-bold'>"+chat.time+"</small>";
    var div1= "<div class='d-flex align-items-center justify-content-between mb-1'>" + h6 + "</div>";
    var div2= "<div class='media-body ml-4'>"+ div1 + p +"</div>";
    var div3= "<div class='media'><img src='/uploads/images/"+chat.picture+"' alt='user' width='50' class='rounded-circle'>"+div2+"</div>";
    var a= "<a href='javascript: showSelectedChatMessages("+chat.id+")' id='"+chat.id+"' class='list-group-item list-group-item-action list-group-item-light rounded-0'>"+div3+"</a>";

    $("#recentChats").prepend(a);
}

//gets receiver email entered by user, and display new chat box
function newMessage(){
    var email= document.getElementById("newMessage").value;
    document.getElementById("newMessage").value="";

    var current=$(".active");
    current.removeClass("text-white");
    current.removeClass("active");
    current.addClass("list-group-item-light");

    document.getElementById("messages-container").innerHTML="<p>Started conversation with "+email+"</p>";
    document.getElementById('send-form').setAttribute("onsubmit", "return newChat('"+email+"')");

}

//get id of chat with the email entered by user
function newChat(email){
    var messageText=document.getElementById('send-form').message;
    if(messageText.value!=""){
        jQuery.ajax({
            url: "chat/new",
            type: "post",
            data: "email="+email,
            success: function(response){
                if(response=="incorrect email" || response=="no data"){
                    $('#alertModal').modal('show');
                }
                else{
                    sendMessage(response, false); //send new message to this chat
                    document.getElementById('send-form').setAttribute("onsubmit", "return sendMessage("+response+", true)");
                }
            }
        });
    }
    
    return false;
}