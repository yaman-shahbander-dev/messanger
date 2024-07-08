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
    $('.wsus__message_paceholder').addClass('d-none');
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


/**
 * -----------------------------------
 * On DOM Load
 * -----------------------------------
 */

$(document).ready(function () {
    $('#select_file').change(function () {
        imagePreview(this, '.profile-image-preview')
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
        IdInfo(dataId);
    });
});
