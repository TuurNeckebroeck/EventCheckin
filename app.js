//https://bootswatch.com/lux/

$(document).ready(function() {
    // Global Settings
    let edit = false;
  
    // Testing Jquery
    console.log('jquery is working');
    fetchTasks();
    $('#task-result').hide();
  
  
    // search key type event
    $('#search').keyup(function() {
      if($('#search').val()) {
        let search = $('#search').val();
        $.ajax({
          url: 'task-search.php',
          data: {search},
          type: 'POST',
          success: function (response) {
            if(!response.error) {
              let tasks = JSON.parse(response);
              let template = '';
              tasks.forEach(task => {
                template += `
                       <li><a href="#" class="task-item">${task.name}</a></li>
                      ` 
              });
              $('#task-result').show();
              $('#container').html(template);
            }
          } 
        })
      }
    });


    // auto show person checkin data
    $('#checkin-code').keyup(function() {
      checkin_code = $('#checkin-code').val();
      if(checkin_code.length == 9 || isNaN(checkin_code)) {
        getAttendeeDataByCheckinCode(checkin_code);
      } else {
        hideAttendeeData();
      }
    });
  
    $('#checkin-form').submit(e => {
      e.preventDefault();
      const postData = {
        checkin_code: $('#checkin-code').val()
      };

      const url = 'checkin-attendee.php';

      $.post(url, postData, (response) => {
        $('#checkin-form').trigger('reset');
        fetchTasks();
        popupCheckin();
        hideAttendeeData();
      });
    });
  
    // Fetching Tasks
    function fetchTasks() {
      $.ajax({
        url: 'get-last-checkins.php',
        type: 'GET',
        success: function(response) {
          const checkins = JSON.parse(response);
          let template = '';
          checkins.forEach(checkin => {
            template += `
                    <tr checkinId="${checkin.id}">
                    <td>${checkin.id}</td>
                    <td>
                    <a href="#" class="attendee-item">
                      ${checkin.name} 
                    </a>
                    </td>
                    <td>${checkin.nb_tickets}</td>
                    <td>${checkin.total_tickets}</td>
                    <td>${checkin.timestamp}</td>
                    
                    <!--
                    <td>
                      <button class="attendee-delete btn btn-danger">
                       Delete
                      </button>
                    </td>
                    -->
                    </tr>
                  `
          });
          $('#attendees_list').html(template);
        }
      });
    }
  
    // Get a Single Task by Id 
    $(document).on('click', '.task-item', (e) => {
      const element = $(this)[0].activeElement.parentElement.parentElement;
      const id = $(element).attr('taskId');
      $.post('task-single.php', {id}, (response) => {
        const task = JSON.parse(response);
        $('#name').val(task.name);
        $('#description').val(task.description);
        $('#taskId').val(task.id);
        edit = true;
      });
      e.preventDefault();
    });
  
    // hide alert
    $(document).on('click', '.close', (e) => {
      console.log("close alert: "+ e);
      // const element = $(this)[0].activeElement.parentElement.parentElement;
      $('#alert_div').css("display", "none");
    });

    function popupCheckin(name) {
      const text = '<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Successfully checked in.</div>';
      $('#alert_div').html(text);
      $('#alert_div').css("display", "visible");
    }

    function getAttendeeDataByCheckinCode(checkin_code) {
      const url = 'get-attendee-data.php';
      const postData = {
        checkin_code: $('#checkin-code').val()
      };

      $.post(url, postData, (response) => {
        if(response.trim() == "false") {
          hideAttendeeData();
          return;
        }

        parsedJson = JSON.parse(response)[0];
        let attName = parsedJson['name'];
        let attCheckedIn = parsedJson['checked_in'];
        let attNbTickets = parsedJson['total_tickets'];

        showAttendeeData(attName, attCheckedIn, attNbTickets);
      });
    }

    function showAttendeeData(name, nbCheckedInTickets, nbTotalTickets) {
      let rowClass = (nbCheckedInTickets<nbTotalTickets) ? "table-success" : "table-danger";

      let attendeeData = `<table class="table">
      <tbody>
        <tr class="${rowClass}">
          <td>${name}</td>
          <td>${nbCheckedInTickets}</td>
          <td>${nbTotalTickets}</td>
        </tr>`;
      
      $("#attendee_data").html(attendeeData);
    }

    function hideAttendeeData() {
      $("#attendee_data").html("");
    }
  });
  
  
