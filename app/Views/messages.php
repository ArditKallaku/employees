<!-- Modal to alert user for incorrect email -->
<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="alertModalLabel">Incorrect email</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        The email you entered is not correct or doesn't belong to any account.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>


<div class="container py-5 px-4">

  <div class="row rounded-lg overflow-hidden shadow">
    <!-- Users box-->
    <div class="col-5 px-0">
      <div class="bg-white">

        <div class="bg-gray px-4 py-2 bg-light">
          <div class="text-right">
            <input type="email" id="newMessage" name="message" placeholder="Enter email" class="">
            <button onclick="javascript: newMessage()" class="btn btn-primary">New message</button>
          </div>
          <span class="h5 mb-0 py-1">Recent</span>
        </div>

        <div class="messages-box">
          <div id="recentChats" class="list-group rounded-0">
            <!-- recent chats here -->
          </div>
        </div>
      </div>
    </div>
    <!-- Chat Box-->
    <div class="col-7 px-0">
      <span id="selectedConversation" style="display:none">0</span> <!-- current chat id -->
      <div class="px-4 py-5 chat-box bg-white" id="messages-container">
        <!-- messages here -->
        <p>You don't have any messages. Start a new conversation</p>
      </div>

      <!-- Typing area -->
      <form class="bg-light" id="send-form">
        <div class="input-group">
          <input type="text" name="message" placeholder="Type a message" aria-describedby="button-addon2" class="form-control rounded-0 border-0 bg-light">
          <div class="input-group-append">
            <button id="button-addon2" type="submit" class="btn btn-link"> <i class="fa fa-paper-plane"></i></button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="/assets/scripts/messages.js"></script>