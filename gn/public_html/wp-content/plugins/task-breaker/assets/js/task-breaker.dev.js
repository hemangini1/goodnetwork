jQuery(document).ready(function($) {
	'use strict'
	$(window).load( function() {  

var __ThriveProjectModel = Backbone.View.extend({
    id: 0,
    project_id: task_breakerProjectSettings.project_id,
    page: 1,
    priority: -1,
    current_page: 1,
    max_page: 1,
    min_page: 1,
    total: 0,
    show_completed: 'no',
    total_pages: 0,
});

var ThriveProjectModel = new __ThriveProjectModel();

var __ThriveProjectView = Backbone.View.extend({

    el: 'body',
    model: ThriveProjectModel,
    search: '',
    template: '',
    events: {
        "click .task_breaker-project-tab-li-item-a": "switchView",
        "click .next-page": "next",
        "click .prev-page": "prev",
        "click #task_breaker-task-search-submit": "searchTasks",
        "change #task_breaker-task-filter-select": "filter"
    },

    switchView: function(e, elementID) {

        $('#task_breaker-project-edit-tab').css('display', 'none');
        $('#task_breaker-project-add-new').css('display', 'none');

        $('.task_breaker-project-tab-li-item').removeClass('active');
        $('.task_breaker-project-tab-content-item').removeClass('active');

        var $active_content = "";

        if (e) {

            var $element = $(e.currentTarget);

            $active_content = $element.attr('data-content');

            // Activate selected tab.
            $element.parent().addClass('active');

            $('div[data-content=' + $active_content + ']').addClass('active');

        } else {

            $(elementID).addClass('active');

            $active_content = $(elementID).attr('data-content');

            $('a[data-content=' + $active_content + ']').parent().addClass('active');
        }
    },

    hideFilters: function() {
        $('#task_breaker-tasks-filter').hide();
    },

    showFilters: function() {
        $('#task_breaker-tasks-filter').show();
    },

    searchTasks: function() {

        var keywords = $('#task_breaker-task-search-field').val();

        if ( 0 === keywords.length ) {
            location.href = '#tasks';
        } else {
            location.href = '#tasks/search/' + encodeURI(keywords);
        }

    },

    filter: function(e) {
        this.model.priority = e.currentTarget.value;

        var currentRoute = Backbone.history.getFragment();

        if (currentRoute != 'tasks') {
            location.href = '#tasks';
        } else {
            this.render();
        }
    },

    next: function(e) {
        e.preventDefault();
        var currPage = this.model.page;
        if (currPage < this.model.max_page) {
            this.model.page = ++currPage;
            location.href = '#tasks/page/' + this.model.page;
        }
    },

    prev: function(e) {
        e.preventDefault();
        var currPage = this.model.page;
        if (currPage > this.model.min_page) {
            this.model.page = --currPage;
            location.href = '#tasks/page/' + this.model.page;
        }
    },

    single: function(ticket_id) {
        this.progress(true);
        var __this = this;
        this.template = 'task_breaker_ticket_single';
        // load the task
        this.renderTask(function( httpResponse ) {

            __this.progress( false );

            var response = JSON.parse( httpResponse );

            if ( response.message == 'fail' ) {
                $('#task_breaker-project-tasks').html("<p class='info' id='message'>"+response.message_long+"</p>");
            }

            if ( response.html ) {
                $('#task_breaker-project-tasks').html(response.html);
            }
        });
    },

    showEditForm: function(task_id) {

        this.progress(true);
        var __this = this;

        var __taskEditor = tinymce.get('task_breakerTaskEditDescription');

        if ( __taskEditor ) {
            __taskEditor.setContent( '' );
        } else {
            $( '#task_breakerTaskEditDescription' ).val( '' );
        }

        $('.task_breaker-project-tab-content-item').removeClass('active');
        $('.task_breaker-project-tab-li-item').removeClass('active');
        $('a#task_breaker-project-edit-tab').css('display', 'block').parent().addClass('active');
        $('#task_breaker-project-edit-context').addClass('active');

        $('#task_breakerTaskId').attr('disabled', true).val('loading...');
        $('#task_breakerTaskEditTitle').attr('disabled', true).val('loading...');
        $("#task_breaker-task-edit-select-id").attr('disabled', true);

        this.model.id = task_id;

        // Render the task.
        this.renderTask( function( httpResponse ) {

            __this.progress( false );

            var response = JSON.parse( httpResponse );

            if ( response.task ) {

                var task = response.task;

                var taskEditor = tinymce.get('task_breakerTaskEditDescription');

                $('#task_breakerTaskId').val(task.id).removeAttr("disabled");
                $('#task_breakerTaskEditTitle').val(task.title).removeAttr("disabled");

                if ( taskEditor ) {
                    taskEditor.setContent( task.description );
                } else {
                    $( '#task_breakerTaskEditDescription' ).val( task.description );
                }

                $( "#task_breaker-task-edit-select-id" ).val( task.priority ).change().removeAttr("disabled");

            }

            return;

        });

    },

    renderTask: function( __callback ) {
        $.ajax({
            url: ajaxurl,
            method: 'get',
            data: {
                action: 'task_breaker_transactions_request',
                method: 'task_breaker_transaction_fetch_task',
                id: this.model.id,
                project_id: this.model.project_id,
                template: this.template,
                nonce: task_breakerProjectSettings.nonce
            },
            success: function( httpResponse ) {
                __callback( httpResponse );
            }
        });
    },

    render: function() {

        var __this = this;
        this.progress(true);

        $.ajax({
            url: ajaxurl,
            method: 'get',
            data: {
                action: 'task_breaker_transactions_request',
                method: 'task_breaker_transaction_fetch_task',
                id: this.model.id,
                project_id: this.model.project_id,
                page: this.model.page,
                search: this.search,
                priority: this.model.priority,
                template: 'task_breaker_the_tasks',
                show_completed: this.model.show_completed,
                nonce: task_breakerProjectSettings.nonce
            },
            success: function( httpResponse ) {

                __this.progress(false);

                var response = JSON.parse( httpResponse );

                if (response.message == 'success') {
                    if (response.task.stats) {
                        // update model max_page and min_page
                        ThriveProjectModel.max_page = response.task.stats.max_page;
                        ThriveProjectModel.min_page = response.task.stats.min_page;
                    }
                    // render the result
                    $('#task_breaker-project-tasks').html(response.html);
                }

                if (0 === response.task.length) {
                    $('#task_breaker-project-tasks').html('<div class="error" id="message"><p>No tasks found. If you\'re trying to find a task, kindly try different keywords and/or filters.</p></div>');
                }

            },
            error: function() {

            }
        });
    },

    initialize: function() {

    },

    progress: function(isShow) {

        var __display = 'none';
        var __opacity = 1;

        if ( isShow ) {
            __display = 'block';
            __opacity = 0.25;
        }

        $('#task_breaker-preloader').css({
            display: __display
        });

        $('#task_breaker-project-tasks').css({
            opacity: __opacity
        });

        return;
    },

    updateStats: function( stats ) {

        var priority = null;
        var task_status = null;

        if ( stats.status ) {
            priority = stats.status.priority;
            task_status = stats.status.task_status;
        }

        if ( task_status ) {
            $('#task-details-status').text( task_status ).removeClass("open close").addClass( task_status.toLowerCase() );
        }

        if ( priority ) {
            $('#task-details-priority').text( priority ).removeClass("normal high critical").addClass( priority.toLowerCase() );
        }

        $( '.task_breaker-total-tasks' ).text( stats.total );
        $( '.task_breaker-remaining-tasks-count' ).text( stats.remaining );
        $( '.task-progress-completed' ).text( stats.completed );
        $( '.task-progress-percentage-label > span' ).text( stats.progress );

        // Update the progress bar css width.
        $( '.task-progress-percentage' ).css({
            width: Math.ceil( ( ( stats.completed / stats.total ) * 100 ) ) + '%'
        });

    }
});

var ThriveProjectView = new __ThriveProjectView();

var __ThriveProjectRoute = Backbone.Router.extend({

    routes: {
        "tasks": "index",
        "tasks/dashboard": "dashboard",
        "tasks/settings": "settings",
        "tasks/completed": "completed_tasks",
        "tasks/add": "add",
        "tasks/edit/:id": "edit",
        "tasks/page/:page": "next",
        "tasks/view/:id": "view_task",
        "tasks/search/:search_keyword": 'search',
    },
    view: ThriveProjectView,
    model: ThriveProjectModel,
    index: function() {

        this.view.switchView(null, '#task_breaker-project-tasks-context');
        this.model.page = 1;
        this.model.id = 0;
        this.model.show_completed = 'no';

        this.view.search = '';
        this.view.render();
    },

    dashboard: function() {
        this.view.switchView(null, '#task_breaker-project-dashboard-context');
    },
    settings: function() {
        this.view.switchView(null, '#task_breaker-project-settings-context');
    },
    add: function() {
        this.view.switchView(null, '#task_breaker-project-add-new-context');
        $('#task_breaker-project-add-new').css('display', 'block');

        if ( tinymce.editors.task_breakerTaskDescription ) {
            tinymce.editors.task_breakerTaskDescription.setContent('');
        }
    },
    completed_tasks: function() {

        this.view.switchView(null, '#task_breaker-project-tasks-context');

        this.model.show_completed = 'yes';
        this.view.render();
    },
    edit: function(task_id) {
        this.view.showEditForm(task_id);
        $('#task_breaker-edit-task-message').html('');
    },
    next: function(page) {
        this.model.page = page;
        this.view.render();
    },
    view_task: function(task_id) {
        this.model.id = task_id;
        this.view.single(task_id);
        this.view.switchView(null, '#task_breaker-project-tasks-context');
    },
    search: function(keywords) {
        this.model.page = 1;
        this.model.id = 0;
        this.view.search = keywords;
        this.view.render();
    }
});

var ThriveProjectRoute = new __ThriveProjectRoute();

ThriveProjectRoute.on('route', function(route) {
    if ('view_task' === route) {
        this.view.hideFilters();
    } else {
        this.view.showFilters();
    }
});

Backbone.history.start();

$('#task_breaker-submit-btn').click(function(e) {

    e.preventDefault();

    var element = $(this);

    element.attr('disabled', true);
    element.text('Loading ...');

    var taskDescription = "";
    var __taskEditor = tinymce.get( 'task_breakerTaskDescription' );

    if ( __taskEditor ) {
       taskDescription =  __taskEditor.getContent();
    } else {
       taskDescription = $( '#task_breakerTaskDescription' ).val();
    }

    $.ajax({
        url: ajaxurl,
        data: {
            
            action: 'task_breaker_transactions_request',
            method: 'task_breaker_transaction_add_ticket',
            
            description: taskDescription,
            
            title: $('#task_breakerTaskTitle').val(),
            milestone_id: $('#task_breakerTaskMilestone').val(),
            priority: $('select#task_breaker-task-priority-select').val(),

            nonce: task_breakerProjectSettings.nonce,

            project_id: task_breakerTaskConfig.currentProjectId,
            user_id: task_breakerTaskConfig.currentUserId
        },

        method: 'post',

        success: function( message ) {

            // Total tasks view.
            var total_tasks = parseInt( $('.task_breaker-total-tasks').text().trim() );

            // Remaining tasks view
            var remaining_tasks = parseInt( $('.task_breaker-remaining-tasks-count').text().trim() );

            message = JSON.parse( message );

           // console.log( message ); 

            if ( message.message === 'success' ) {

                element.text('Save Task');

                element.removeAttr('disabled');

                $('#task_breakerTaskDescription').val('');

                $('#task_breakerTaskTitle').val('');
                
                ThriveProjectView.updateStats( message.stats );

                location.href = "#tasks/view/" + message.response.id;


            } else {

                $('#task_breaker-add-task-message').html('<p class="error">'+message.response+'</p>').show().addClass('error');

              

                element.text('Save Task');
                
                element.removeAttr('disabled');

            }
        },
        error: function() {

        }
    }); // end $.ajax
}); // end $('#task_breaker-submit-btn').click()

$('#task_breaker-edit-btn').click(function(e) {

    e.preventDefault();

    var element = $(this);

    element.attr('disabled', true);
    element.text('Loading ...');

    var taskDescription = "";
    var taskDescriptionObject = tinymce.get( 'task_breakerTaskEditDescription' );

    if ( taskDescriptionObject ) {
        taskDescription = taskDescriptionObject.getContent();
    } else {
        taskDescription = $('#task_breakerTaskEditDescription').val();
    }

    $.ajax({

        url: ajaxurl,
        data: {

            description: taskDescription,
            nonce: task_breakerProjectSettings.nonce,
            project_id: task_breakerTaskConfig.currentProjectId,
            user_id: task_breakerTaskConfig.currentUserId,

            action: 'task_breaker_transactions_request',
            method: 'task_breaker_transaction_edit_ticket',

            title: $('#task_breakerTaskEditTitle').val(),
            milestone_id: $('#task_breakerTaskMilestone').val(),
            id: $('#task_breakerTaskId').val(),
            priority: $('select[name="task_breaker-task-edit-priority"]').val()

        }, 

        method: 'post',

        success: function( httpResponse ) {

            var response = JSON.parse( httpResponse );

            var message = "<p class='success'>Task successfully updated <a href='#tasks/view/" + response.id + "'>&#65515; View</a></p>";

            if ('fail' === response.message && 'no_changes' !== response.type) {

                message = "<p class='error'>There was an error updating the task. All fields are required.</a></p>";

            }
 
            $('#task_breaker-edit-task-message').html(message).show();

            element.attr('disabled', false);

            element.text('Update Task');

            return;

        },
        
        error: function() {

            // Todo: Better handling of http errors and timeouts.
            console.log('An Error Occured [task_breaker.js]#311');

            return;
        }
    });
}); // end $('#task_breaker-edit-btn').click()

 // Delete Task Single
 $('body').on('click', '#task_breaker-delete-btn', function() {

    var _delete_confirm = confirm("Are you sure you want to delete this task? This action is irreversible");

    if (!_delete_confirm) {
       return;
    }

    var $element = $(this);

    var task_id = parseInt( ThriveProjectModel.id );

    var task_project_id = parseInt( ThriveProjectModel.project_id );

    var __http_params = {

       action: 'task_breaker_transactions_request',
       method: 'task_breaker_transaction_delete_ticket',
       id: task_id,
       project_id: task_project_id,
       nonce: task_breakerProjectSettings.nonce

   };

   ThriveProjectView.progress(true);

   $element.text('Deleting ...');

   $.ajax({

       url: ajaxurl,
       data: __http_params,
       method: 'post',
       success: function( httpResponse ) {
            
            var response = JSON.parse( httpResponse );
           
            ThriveProjectView.progress(false);

            ThriveProjectView.updateStats( response.stats );

            location.href = "#tasks";

            ThriveProjectView.switchView(null, '#task_breaker-project-tasks-context');

            $element.text('Delete');

       },

       error: function() {
           ThriveProjectView.progress(false);
           location.href = "#tasks";
           ThriveProjectView.switchView(null, '#task_breaker-project-tasks-context');
           $element.text('Delete');

       }
   });
 }); // End Delete Task

  $('body').on('click', '#updateTaskBtn', function() {

      var comment_ticket_id = ThriveProjectModel.id,
          comment_details = $('#task-comment-content').val(),
          task_priority = $('#task_breaker-task-priority-update-select').val(),
          comment_completed = $('input[name=task_commment_completed]:checked').val(),
          task_project_id = parseInt( ThriveProjectModel.project_id );

      if (0 === comment_ticket_id) {
          return;
      }

      // notify the user when submitting the comment form
      ThriveProjectView.progress(true);

      var __http_params = {
          action: 'task_breaker_transactions_request',
          method: 'task_breaker_transaction_add_comment_to_ticket',
          ticket_id: comment_ticket_id,
          priority: task_priority,
          details: comment_details,
          completed: comment_completed,
          project_id: task_project_id,
          nonce: task_breakerProjectSettings.nonce 
      };

      $.ajax({
          url: ajaxurl,
          data: __http_params,
          method: 'post',
          success: function( httpResponse ) {

              var response = JSON.parse( httpResponse );

              ThriveProjectView.progress( false );

              $('#task-comment-content').val('');
              $('#task-lists').append(response.result);

            
              if ("yes" === comment_completed) {

                  // disable old radios
                  $('#ticketStatusInProgress').attr('disabled', true).attr('checked', false);
                  $('#ticketStatusComplete').attr('disabled', true).attr('checked', false);
                  $('#comment-completed-radio').addClass('hide');
                  // enable new radios
                  $('#ticketStatusCompleteUpdate').attr('disabled', false).attr('checked', true);
                  $('#ticketStatusReOpenUpdate').attr('disabled', false);
                  $('#task_breaker-comment-completed-radio').removeClass('hide');

              }

              if ( "reopen" === comment_completed ) {

                  // Enable old radios
                  $('#ticketStatusInProgress').attr('disabled', false).attr('checked', true);
                  $('#ticketStatusComplete').attr('disabled', false).attr('checked', false);
                  $('#comment-completed-radio').removeClass('hide');
                  // Disable new radios
                  $('#ticketStatusCompleteUpdate').attr('disabled', true).attr('checked', false);
                  $('#ticketStatusReOpenUpdate').attr('disabled', true);
                  $('#task_breaker-comment-completed-radio').addClass('hide');

              }
              // console.log(response.stats);
              ThriveProjectView.updateStats( response.stats );
          },
          error: function() {

              ThriveProjectView.progress(false);
          }
      });
  }); // end UpdateTask

// Delete Comment Event.
$('body').on('click', 'a.task_breaker-delete-comment', function(e) {

    e.preventDefault();

    // Ask the user to confirm if he/she really wanted to delete the task comment.
    var confirm_delete = confirm("Are you sure you want to delete this comment? This action is irreversible. ");

    // Exit if the user decided to cancel the task comment.
    if (!confirm_delete) {
        return false;
    }

    var $element = $(this);

    var comment_ticket = parseInt($(this).attr('data-comment-id'));

    var __http_params = {
        action: 'task_breaker_transactions_request',
        method: 'task_breaker_transaction_delete_comment',
        comment_id: comment_ticket,
        nonce: task_breakerProjectSettings.nonce
    };

    // Send request to server to delete the comment.
    ThriveProjectView.progress(true);

    $.ajax({
        url: ajaxurl,
        data: __http_params,
        method: 'post',
        success: function( httpResponse ) {

            ThriveProjectView.progress(false);

            var response = JSON.parse( httpResponse );

            if (response.message == 'success') {

                $element.parent().parent().parent().parent().fadeOut(function() {
                    $(this).remove();
                });

            } else {

                this.error();
                
            }
        },
        error: function() {
            ThriveProjectView.progress(false);
            $element.parent().append('<p class="error">Transaction Error: There was an error trying to delete this comment.</p>');
        }
    });
}); // end Delete Comment

/**
 * Add new project script
 *
 * @Todo: Current handle for adding project is inside archive.js
 */

// Update Project
$('body').on('click', '#task_breakerUpdateProjectBtn', function() {

    var element = $(this);

    var projectContent = "";

    var __projectContentObj = tinymce.get( 'task_breakerProjectContent' );

        if ( __projectContentObj ) {

            projectContent = __projectContentObj.getContent();

        } else {

            projectContent = $('#task_breakerProjectContent').val();

        }

    var __http_params = {
        action: 'task_breaker_transactions_request',
        method: 'task_breaker_transactions_update_project',
        id: parseInt( $('#task_breaker-project-id').val() ),
        title: $( '#task_breaker-project-name' ).val(),
        content: projectContent,
        group_id: parseInt( $('select[name=task_breaker-project-assigned-group]').val() ),
        nonce: task_breakerProjectSettings.nonce
    };

    element.attr('disabled', true).text('Updating ...');

    ThriveProjectView.progress(true);

    $('.task_breaker-project-updated').remove();

    $.ajax({
        url: ajaxurl,
        data: __http_params,
        method: 'post',
        success: function( httpResponse ) {

            var response = JSON.parse( httpResponse );

            ThriveProjectView.progress(false);

            element.attr('disabled', false).text('Update Project');

            if (response.message === 'success') {

                // Update the project title.
                $('article .entry-header > .entry-title').text($('#task_breaker-project-name').val());

                element.parent().parent().prepend(
                    '<div id="message" class="task_breaker-project-updated success updated">' +
                    '<p>Project details successfully updated.</p>' +
                    '</div>'
                );

                location.reload();

            } else {

                if ("authentication_error" === response.type ) {

                    element.parent().parent().prepend(
                        '<div id="message" class="task_breaker-project-updated error updated">' +
                        '<p>Only group administrators and moderators can update the project settings.</p>' +
                        '</div>'
                    );

                } else {

                    element.parent().parent().prepend(
                        '<div id="message" class="task_breaker-project-updated success updated">' +
                        '<p>There was an error saving the project. All fields are required.</p>' +
                        '</div>'
                    );

                }

            }

            ThriveProjectView.progress(false);

            setTimeout(function() {

                $('.task_breaker-project-updated').fadeOut();

            }, 3000);

            return;

        },

        error: function() {

            alert('connection failure');
            return;

        }
    });
}); // Project Update End.

 $('body').on('click', '#task_breakerDeleteProjectBtn', function() {


     if ( !confirm('Are you sure you want to delete this project? All the tickets under this project will be deleted as well. This action cannot be undone.')) {
         return;
     }

     var project_id = $('#task_breaker-project-id').val();

     var __http_params = {
         action: 'task_breaker_transactions_request',
         method: 'task_breaker_transactions_delete_project',
         id: project_id,
         nonce: task_breakerProjectSettings.nonce
     };

     $(this).text('Deleting...');

     $.ajax({

         url: ajaxurl,

         method: 'post',

         data: __http_params,

         success: function( httpResponse ) {

             var response = JSON.parse( httpResponse );

             if (response.message == 'success') {

                 window.location = response.redirect;

             } else {

                this.error();

             }

             return;

         },

         error: function() {

            alert('There was an error trying to delete this post. Try again later.');

         }
     });

 });

}); // end $(window).load();
}); // end jQuery(document).ready(); 