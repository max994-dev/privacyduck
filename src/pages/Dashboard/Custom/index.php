<?php
  
  $conn = getDBConnection();
  $sql = "SELECT * FROM custom_removal WHERE user_id = ? And state = 1";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $_SESSION["user_id"]);
  $stmt->execute();
  $result = $stmt->get_result();
  $customRemoval = $result->fetch_assoc();
  $stmt->close();
  $conn->close();
  if ($customRemoval == null) {
    $customRemoval = [
      "step" => 0,
      "url" => "",
      "data_type" => "",
      "estimated_time" => "",
      "status" => 3
    ];
  }
?>
  <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-[41px]">
    <!-- Chat Assistant -->
    <div class="bg-white rounded-2xl shadow p-6 flex flex-col">
      <h2 class="text-xl font-semibold mb-4">Custom Removal Assistant</h2>
      <div class="flex items-center mb-4 text-green-600">
        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span> Online
      </div>
      <div id="chatContainer" class="h-[50vh] overflow-y-scroll">
        <div id="waitingElement" class="flex items-center justify-center">waiting...</div>
        <div id="viewMoreElement" class="flex items-center justify-center cursor-pointer" onclick="viewMore()">View More</div>
        <div id="messageContainer">
        </div>
      </div>

      <div class="mt-4">
        <div class="flex items-center gap-2">
          <input onkeydown="if (event.key === 'Enter') sendMessage()" id="messageInput" type="text" placeholder="Type your message..." class="flex-1 border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
          <button id="sendMessageButton" class="bg-green-500 hover:bg-green-600 text-white rounded-full p-2" onclick="sendMessage()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Tracking Progress -->
    <div class="bg-white rounded-2xl shadow p-6">
      <h2 class="text-xl font-semibold mb-4">Tracking Progress</h2>

      <!-- Progress Bar -->
      <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
        <div class="bg-green-400 h-3 rounded-full" style="width: <?= $customRemoval["step"]*100/5?>%"></div>
      </div>

      <!-- Steps -->
      <div class="space-y-2 mb-6">
        <?php foreach ([
          "Information collected",
          "Case assessment completed",
          "Platform verification",
          "Content removal",
          "Confirmation & report"
        ] as $key => $value) {
          ?>
          <div class='flex items-center gap-2 <?= $customRemoval["step"] > $key? 'text-green-600': '' ?>'>
            <span class='w-3 h-3 rounded-full bg-gray-300 <?= $customRemoval["step"] > $key? 'bg-green-500': '' ?>'></span>
            <span><?= $value ?></span>
          </div>
          <?php
        } ?>
      </div>

      <!-- Details -->
      <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
          <h3 class="text-sm text-gray-500">Website</h3>
          <p class="text-sm font-medium text-blue-600 break-words">
            <a href="https://<?= $customRemoval["url"]?>" target="_blank">
              REDFIN<br>
              <?= $customRemoval["url"]?>
            </a>
          </p>
        </div>
        <div>
          <h3 class="text-sm text-gray-500">Data Type</h3>
          <p class="text-sm font-medium text-gray-800"><?= $customRemoval["data_type"]?></p>
        </div>
        <div>
          <h3 class="text-sm text-gray-500">Estimated Time</h3>
          <p class="text-sm font-medium text-gray-800"><?= $customRemoval["estimated_time"]?></p>
        </div>
        <div>
          <h3 class="text-sm text-gray-500">Status</h3>
          <span id="status" class="inline-block bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full"><?php
            $statuses = [
                0 => "NONE",
                1 => "IN_PROGRESS",
                2 => "DONE"
            ];
            $label = $statuses[$customRemoval["status"]] ?? "EMERGENCY STOP";
            echo $label;
          ?></span>
        </div>
      </div>

      <button id="emergencyStopButton" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg <?= $customRemoval["status"] != 3? '': 'hidden' ?>" onclick="emergencyStop()">
        Emergency Stop
      </button>
    </div>

  </div>
<script>
  function receivedMessageContainer(message, time){
    return `<div class="bg-gray-100 p-4 rounded-lg mt-1">
      <p class="text-sm text-gray-800">
        ${message}
      </p>
      <p class="text-xs text-gray-500 mt-1">${time}</p>
    </div>`
  }
  function sentMessageContainer(message, time){
    return `<div class="bg-blue-100 p-4 rounded-lg self-end mt-1">
      <p class="text-sm text-gray-800">
        ${message}
      </p>
      <p class="text-xs text-gray-500 mt-1">${time}</p>
    </div>`
  }
  function emergencyStop(){
    $("#emergencyStopButton").prop("disabled", true);
    $.post("/emergency_stop", function(data){
      $("#emergencyStopButton").hide();
      $("#status").text("EMERGENCY STOP");
    });
  }
  function viewMore(){
    window.page++;
    $("#waitingElement").show();
    $("#viewMoreElement").hide();
    getList();
  }
  window.page = 1;
  function getList(first=false){
    $.get("/get_messages", {page: window.page}, function(data){
      $("#waitingElement").hide();
      if (data.length) {
        $("#viewMoreElement").show();
      }
      const scrollHeight = $("#chatContainer").prop("scrollHeight");
      data = data.reverse();
      let list = "";
      for (let i = 0; i < data.length; i++) {
        const time = new Date(new Date(data[i].time).valueOf() - (new Date().getTimezoneOffset())*60*1000).toLocaleString(); 
        if (data[i].received) {
          list += receivedMessageContainer(data[i].message, time);
        } else {
          list += sentMessageContainer(data[i].message, time);
        }
      }
      $("#messageContainer").prepend(list);
      if (first) {
        $("#chatContainer").scrollTop($("#chatContainer").prop("scrollHeight"));
      }
      else {
        $("#chatContainer").scrollTop($("#chatContainer").prop("scrollHeight") - scrollHeight);
      }
    });
  }
  getList(true)
  function sendMessage(){
    const message = $("#messageInput").val();
    if (message == "") return;
    $("#sendMessageButton").prop("disabled", true);
    addList(message);
    $("#messageInput").val("");

    $.post("/send_message", {message: message}, function(data){
      $("#sendMessageButton").prop("disabled", false);
    });
  }
  function addList(message){
    const list = sentMessageContainer(message, new Date().toLocaleString());
    $("#messageContainer").append(list);
    $("#chatContainer").scrollTop($("#chatContainer").prop("scrollHeight"));
  }
  $("#chatContainer").on("scroll", function(){
    if ($(this).scrollTop() == 0) {
      viewMore();
    }
  });
</script>