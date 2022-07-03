
require('./bootstrap');

import Echo from "laravel-echo"
import { isEmpty } from "lodash";
import url from "socket.io-client/lib/url";

window.io = require('socket.io-client');

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
});

let onlineUserLength = 0 ;

window.Echo.join('online')


    .here((users) => {

        onlineUserLength = users.length ;
        if(onlineUserLength > 1){
            $('#no-onlien-users').css('display','none'); 
        }
   


        let user_id = $('meta[name=user_id]').attr('content');
      
        users.forEach(function(user) {
           
            if(user.id == user_id){
                return ;
            }
            $('#online-users').append(`<li id="user-${user.id}" class="list-group-item"><span class="icon icon-circle text-success"></span> ${user.name}</li>`);
       
        });
        console.log(users);
    })
    .joining((user) => {

        onlineUserLength ++;
        $('#no-onlien-users').css('display','none'); 
        $('#online-users').append(`<li id="user-${user.id}" class="list-group-item"><span class="icon icon-circle text-success"></span> ${user.name}</li>`);
    })
    .leaving((user) => {
        onlineUserLength --;
        if(onlineUserLength == 1){
            $('#no-onlien-users').css('display','block');
        }
        $('#user-' + user.id ).remove();
    });
/*
   
    $('#chat-text').off().on('keypress  keyup',function(event){
        
    
        let request; 
        if(event.which == 13){
           
            event.preventDefault();
            event.stopPropagation();
            event.disabled = true;
            let ms = $(this).val();
            let url = $(this).data('url');
          
            let data = {
                    '_token': $('meta[name=csrf-token]').attr('content'),
                    body:ms
            };

     
  
            $.ajax({
                url:url,
                method:'POST',
                data:data ,
                success:function() {
                   
                }
                
            });





 
        }

 

  
    });
*/


    $(document).on("keypress.key102", function(event) {
        if ($('#chat-text').is(':visible')) {
    
            if (event.which == 13) {
               
                event.preventDefault();
                let ms = $('#chat-text').val();
                let url = $('#chat-text').data('url');
              let userName = $('meta[name=userName]').attr('content')
                let data = {
                        '_token': $('meta[name=csrf-token]').attr('content'),
                        body:ms
                };
                $('#chat').append(`
                <div class="mt-2 w-50 text-black p-3 rounded  bg-primary" style ="float:right;">
                <p>name: ${userName}</p>
                <p>${ms}</p>
                </div>
                <div style="clear:both"></div>
                `);

                $.ajax({
                    url:url,
                    method:'POST',
                    data:data ,  
                });
               
                $('#chat-text').val('');
           
            }
    
        }
        else {
            if (event.which == 13) {
                
                return;
            }
        }
    });


    window.Echo.channel('laravel_database_chat-group')
    .listen('MessagesDelivered', (event) => {
     
        $('#chat').append(`
        <div class="mt-2 w-50 text-black p-3 rounded  bg-warning" style ="float:left;">
        <p>name: ${event.messages.users.name}</p>
        <p>${event.messages.body}</p>
        </div>
        <div style="clear:both"></div>
        `);
       
    });
 