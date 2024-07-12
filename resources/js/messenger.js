/**
 * -----------------------------------
 * Global Variables
 * -----------------------------------
 */

var temporaryMsgId = 0;
const messageForm = $(".message-form");
const messageInput = $(".message-input");
const csrf_token = $("meta[name=csrf_token]").attr("content");
const messageBoxContainer = $(".wsus__chat_area_body");
const messengerContactBox = $(".messenger-contacts");
const authId = $("meta[name=auth_id]").attr("content");
const url = $("meta[name=url]").attr("content");
const getMessengerId = () => $("meta[name=id]").attr("content");
const setMessengerId = (id) => $("meta[name=id]").attr("content", id);
/**
 * -----------------------------------
 * Reusable Functions
 * -----------------------------------
 */

function imagePreview(input, selector) {
    if (input.files && input.files[0]) {
        var render = new FileReader();

        render.onload = function (e) {
            $(selector).attr('src', e.target.result)
        }

        render.readAsDataURL(input.files[0]);
    }
}

let searchPage = 1;
let noMoreDataSearch = false;
let searchTempVal = "";
let setSearchLoading = false;
function searchUsers(query) {
    if (query !== searchTempVal) {
        searchPage = 1;
        noMoreDataSearch = false;
    }
    searchTempVal = query;

    if (!noMoreDataSearch && !setSearchLoading) {
        $.ajax({
            method: 'GET',
            url: '/messenger/search',
            data: {
                query: query,
                page: searchPage
            },
            beforeSend: function () {
                setSearchLoading = true;

                let loader =
                    '<div class="text-center search-loader">' +
                        '<div class="spinner-border text-primary" role="status">' +
                        '<span class="visually-hidden">Loading...</span>' +
                        '</div>' +
                    '</div>';

                $('.user_search_list_result').append(loader);
            },
            success: function (data) {
                setSearchLoading = false;
                $('.user_search_list_result').find('.search-loader').remove();

                if (searchPage < 2) {
                    $('.user_search_list_result').html(data.data.data);
                } else {
                    $('.user_search_list_result').append(data.data.data);
                }
                noMoreDataSearch = searchPage >= data.data.last_page;
                if (!noMoreDataSearch) searchPage++;
            },
            error: function (xhr, status, error) {
                setSearchLoading = false;
                $('.user_search_list_result').find('.search-loader').remove();
            }
        })
    }
}

function actionOnScroll(selector, callback, topScroll = false) {
    $(selector).on('scroll', function () {
        let element = $(this).get(0);
        const condition = topScroll ? element.scrollTop === 0 : element.scrollTop + element.clientHeight >= element.scrollHeight;

        if (condition) {
            callback();
        }
    })
}

function debounce(callback, delay) {
    let timerId;
    return function (...args) {
        clearTimeout(timerId);
        timerId = setTimeout(() => {
            callback.apply(this, args);
        }, delay)
    }
}

function enableChatBoxLoader() {
    $('.wsus__message_paceholder').removeClass('d-none');
}

function disableChatBoxLoader() {
    $('.wsus__chat_app').removeClass('show_info');
    $('.wsus__message_paceholder').addClass('d-none');
    $('.wsus__message_paceholder.black').addClass('d-none');
    $('.wsus__message_paceholder.black').removeClass('black');
}

function sendMessage() {
    temporaryMsgId++;
    let tempId = `temp_${temporaryMsgId}`;
    let hasAttachment = !!$(".attachment-input").val();
    const inputValue = messageInput.val();

    if (inputValue.length > 0 || hasAttachment) {
        const formData = new FormData($(".message-form")[0]);
        formData.append("id", getMessengerId());
        formData.append("temporaryMsgId", tempId);
        formData.append("_token", csrf_token);

        $.ajax({
            method: 'POST',
            url: '/messenger/send-message',
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false,
            beforeSend: function () {
                if (hasAttachment) {
                    messageBoxContainer.append(sendTemplateMessageCard(inputValue, tempId, true));
                } else {
                    messageBoxContainer.append(sendTemplateMessageCard(inputValue, tempId));
                }
                scrollToBottom(messageBoxContainer);
                messageFormReset();
            },
            success: function (data) {
                updateContactItem(getMessengerId());

                const tempMessageCardElement = messageBoxContainer.find(`.message-card[data-id=${data.data.tempID}]`);
                tempMessageCardElement.before(data.data.message);
                tempMessageCardElement.remove();
            },
            error: function (xhr, status, error) {

            }
        });
    }
}

function sendTemplateMessageCard(message, tempId, attachment = false) {
    if (attachment) {
        return `
            <div class="wsus__single_chat_area message-card" data-id="${tempId}">
                <div class="wsus__single_chat chat_right">
                    <div class="pre_loader">
                        <div class="spinner-border text-light" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    ${message.length > 0 ? `<p class="messages">${message}</p>` : ''}

                    <span class="clock"><i class="fas fa-clock"></i> now</span>
                </div>
            </div>
        `;
    } else {
        return `
            <div class="wsus__single_chat_area message-card" data-id="${tempId}">
                <div class="wsus__single_chat chat_right">
                    <p class="messages">${message}</p>
                    <span class="clock"><i class="fas fa-clock"></i> now</span>
                </div>
            </div>
        `;
    }
}

function receiveMessageCard(e) {
    if (e.attachment) {
        return `
            <div class="wsus__single_chat_area message-card" data-id="${e.id}">
                <div class="wsus__single_chat">
                    <a class="venobox" data-gall="gallery01" href="${url + e.attachment}">
                        <img src="${url + e.attachment}" alt="" class="img-fluid w-100">
                    </a>

                    ${e.body.length > 0 ? `<p class="messages">${e.body}</p>` : ''}
                </div>
            </div>
        `;
    } else {
        return `
            <div class="wsus__single_chat_area message-card" data-id="${e.id}">
                <div class="wsus__single_chat">
                    <p class="messages">${e.body}</p>
                </div>
            </div>
        `;
    }
}

function messageFormReset() {
    $('.attachment-block').addClass('d-none');
    $(".emojionearea-editor").text("");
    messageForm.trigger("reset");
}

let messagePage = 1;
let noMoreMessages = false;
let messagesLoading = false;
function fetchMessages(id, newFetch = false) {

    if (newFetch) {
        messagePage = 1;
        noMoreMessages = false;
    }

    if (!noMoreMessages && !messagesLoading) {
        $.ajax({
            method: 'GET',
            url: '/messenger/fetch-messages',
            data: {
                _token: csrf_token,
                id: id,
                page: messagePage,

            },
            beforeSend: function () {
                messagesLoading = true;
                let loader =
                    '<div class="text-center messages-loader">' +
                    '<div class="spinner-border text-primary" role="status">' +
                    '<span class="visually-hidden">Loading...</span>' +
                    '</div>' +
                    '</div>';

                messageBoxContainer.prepend(loader);
            },
            success: function (data) {
                messagesLoading = false;
                messageBoxContainer.find(".messages-loader").remove();

                makeSeen();

                if (messagePage === 1) {
                    messageBoxContainer.html(data.data.messages);
                    scrollToBottom(messageBoxContainer);
                } else {
                    const lastMsg = $(messageBoxContainer).find(".message-card").first();
                    const currOffset = lastMsg.offset().top - messageBoxContainer.scrollTop();

                    messageBoxContainer.prepend(data.data.messages);
                    messageBoxContainer.scrollTop(lastMsg.offset().top - currOffset);
                }

                noMoreMessages = messagePage >= data.data.last_page;
                if (!noMoreMessages) messagePage++;
            },
            error: function (xhr, status, error) {

            }
        });
    }
}

let contactsPage = 1;
let noMoreContacts = false;
let contactLoading = false;
function getContacts() {
    if (!contactLoading && !noMoreContacts) {
        $.ajax({
            method: 'GET',
            url: '/messenger/fetch-contacts',
            data: {
                page: contactsPage
            },
            beforeSend: function() {
                contactLoading = true;
                let loader =
                    '<div class="text-center contact-loader">' +
                    '<div class="spinner-border text-primary" role="status">' +
                    '<span class="visually-hidden">Loading...</span>' +
                    '</div>' +
                    '</div>';
                messengerContactBox.append(loader);
            },
            success: function (data) {
                contactLoading = false;
                messengerContactBox.find('.contact-loader').remove();
                if (contactsPage < 2) {
                    messengerContactBox.html(data.data.contacts)
                } else {
                    messengerContactBox.append(data.data.contacts)
                }

                noMoreContacts = contactsPage >= data.data.last_page;
                if (!noMoreContacts) contactsPage++;
            },
            error: function (xhr, status, error) {
                contactLoading = false;
                messengerContactBox.find('.contact-loader').remove();
            }
        });
    }
}

function scrollToBottom(container) {
    $(container).stop().animate({
        scrollTop: $(container)[0].scrollHeight
    });
}

function updateContactItem(userId) {
    // if (user_id != authId) {
        $.ajax({
            method: 'GET',
            url: '/messenger/update-contact-item',
            data: {
                userId: userId
            },
            success: function (data) {
                messengerContactBox.find(`.messenger-list-item[data-id="${userId}"]`).remove();
                messengerContactBox.prepend(data.data.contact_item);
                if (userId === getMessengerId()) updateSelectedContent(userId)
            },
            error: function (xhr, status, error) {

            }
        });
    // }
}

function updateSelectedContent(userId) {
    $('.messenger-list-item').removeClass('active');
    $(`.messenger-list-item[data-id="${userId}"]`).addClass('active');
}

function makeSeen() {
    $(`.messenger-list-item[data-id="${getMessengerId()}"]`).find('.unseen_count').remove();

    $.ajax({
        method: 'PUT',
        url: '/messenger/make-seen',
        data: {
            _token: csrf_token,
            id: getMessengerId()
        },
        success: function (data) {

        },
        error: function (xhr, status, error) {

        }
    })
}


function star() {
    $(".favourite").toggleClass('active');

    $.ajax({
        method: 'POST',
        url: '/messenger/favorite',
        data: {
            _token: csrf_token,
            id: getMessengerId()
        },
        success: function (data) {
            if (data.data.status == 'added') {
                notyf.success("Added to the favorite list!");
            } else {
                notyf.success("Removed from the favorite list!");
            }
        },
        error: function (xhr, status, error) {

        }
    })
}

function fetchFavoriteList() {
    $.ajax({
        method: 'GET',
        url: '/messenger/fetch-favorite',
        data: {},
        success: function (data) {
            $(".favourite_user_slider").html(data.data.favorite_list);
        },
        error: function (xhr, status, error) {

        }
    })
}

/**
 * -----------------------------------
 * Fetch id data of the user and update the view
 * -----------------------------------
 */
function IdInfo(id) {
    $.ajax({
        method: 'GET',
        url: '/messenger/user-info',
        data: {
            id: id
        },
        beforeSend: function () {
            NProgress.start();
            enableChatBoxLoader();
        },
        success: function (data) {
            fetchMessages(data.data.id, true);

            data.data.favorite ? $(".favourite").addClass('active') : $(".favourite").removeClass('active');

            $(".wsus__chat_info_gallery").html("");

            if (data.data.shared_photos) {
                $(".nothing_share").addClass("d-none");
                $(".wsus__chat_info_gallery").html(data.data.shared_photos);
            } else {
                $(".nothing_share").removeClass("d-none");
            }

            $('.messenger-header').find('img').attr('src', data.data.avatar);
            $('.messenger-header').find('h4').text(data.data.name);

            $('.messenger-info-view .user_photo').find('img').attr('src', data.data.avatar);
            $('.messenger-info-view').find('.user_name').text(data.data.name);
            $('.messenger-info-view').find('.user_unique_name').text(data.data.user_name);

            NProgress.done();
            disableChatBoxLoader();
        },
        error: function () {
            disableChatBoxLoader();
        }
    });
}

function deleteMessage(message_id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                method: 'DELETE',
                url : '/messenger/delete-message',
                data: {
                    _token: csrf_token,
                    messageId: message_id
                },
                beforeSend: function () {
                    $(`.message-card[data-id="${message_id}"]`).remove();
                },
                success: function (data) {
                    updateContactItem(getMessengerId());
                },
                error: function (xhr, status, error) {

                }
            });
            Swal.fire({
                title: "Deleted!",
                text: "Your file has been deleted.",
                icon: "success"
            });
        }
    });
}

function playNotificationSound() {
    const sound = new Audio(`/default/message-sound.mp3`);
    sound.play();
}


window.Echo.private('message.' + authId)
    .listen("Message", (e) => {
        console.log(e);
        updateContactItem(e.message.from_id);
        playNotificationSound();
        let message = receiveMessageCard(e.message)
        messageBoxContainer.append(message);
    });

/**
 * -----------------------------------
 * On DOM Load
 * -----------------------------------
 */

$(document).ready(function () {
    if (window.innerWidth < 768) {
        $("body").on("click", ".messenger-list-item", function () {
            $(".wsus__user_list").addClass("d-none");
            disableChatBoxLoader();
        });
    }

    getContacts();
    // fetchFavoriteList();

    $('#select_file').change(function () {
        imagePreview(this, '.profile-image-preview');
    });

    const debouncedSearch = debounce(function () {
        const value = $('.user_search').val();
        searchUsers(value);
    }, 500);

    $('.user_search').on('keyup', function () {
        let query = $(this).val();
        if (query.length > 0) {
            debouncedSearch();
        }
    })

    actionOnScroll(".user_search_list_result", function () {
        let value = $('.user_search').val();
        searchUsers(value);
    });

    $("body").on('click', '.messenger-list-item', function () {
        const dataId = $(this).attr('data-id');
        updateSelectedContent(dataId);
        setMessengerId(dataId);
        IdInfo(dataId);
    });

    $(".message-form").on("submit", function (e) {
        e.preventDefault();
        sendMessage();
    });

    $('.attachment-input').change(function () {
        imagePreview(this, '.attachment-preview');
        $('.attachment-block').removeClass('d-none');
    });

    $('.cancel-attachment').on('click', function () {
        messageFormReset();
    });

    actionOnScroll(".wsus__chat_area_body", function () {
        fetchMessages(getMessengerId())
    }, true);

    actionOnScroll(".messenger-contacts", function () {
        getContacts();
    });

    $(".favourite").on("click", function (e) {
        e.preventDefault();
        star();
    })

    $("body").on("click", ".delete-message", function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        deleteMessage(id);
    })
})
