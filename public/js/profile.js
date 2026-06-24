$(document).ready(function () {

    const pathname = window.location.pathname;

    // --------------------GLOBAL--------------------

    $("#copyMeetingLink").on("click", function () {
        navigator.clipboard.writeText(this.getAttribute("data-link"));
        $('.copy-text').text(languages.copied_text);
        setTimeout(() => {
            $('.copy-text').text(languages.copy_link);
        }, 2000);
    });

    setTimeout(function () {
        $('#success-alert').fadeOut(500, function () {
            $(this).remove();
        });
    }, 3000);

    function showToast(message, type = "success") {
        const targetDiv = document.querySelector('.showToastAbove');

        if (!targetDiv) {
            return;
        }

        const alert = document.createElement('div');
        if (type == "error") {
            alert.className = 'alert alert-danger alert-dismissible fade show';
        } else {
            alert.className = 'alert alert-success alert-dismissible fade show';
        }
        alert.role = 'alert';
        alert.innerHTML =
            message;

        targetDiv.parentNode.insertBefore(alert, targetDiv);

        setTimeout(() => {
            alert.classList.remove('show');
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500);
        }, 3000);
    }
    
    // ---------------------API TOKEN COPY------------------------

    if (pathname == "/profile/api-token") {
        $(".copyApiTokenButton").on("click", function () {
            let token = $(this).data("token");
            let inp = document.createElement("textarea");
            inp.style.position = "absolute";
            inp.style.left = "-9999px";
            document.body.appendChild(inp);
            inp.value = token;
            inp.select();
            document.execCommand("copy");
            inp.remove();
            showToast(languages.api_token_copied);
        });
    }

    // -----------------------TOGGLE TFA------------------------
    if (pathname == "/profile/tfa") {
        //update user status via dropdown
        $(document).on("change", ".toggle-user-tfa", function () {
            const toggleTfa = $(this);
            toggleTfa.prop("disabled", true);

            const userTfa = $(this).prop("checked") ? "active" : "inactive";
            const userId = $(this).data("id");

            $.ajax({
                type: "POST",
                url: "/profile/update-tfa",
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                    userTfa,
                    userId,
                },
                success: function (response) {
                    if (response.success) {
                        showToast(response.message);
                    } else {
                        showToast(response.message, 'error');
                    }
                },
                error: function (xhr, status, error) {
                    showToast("Something went wrong, please try again!");
                },
                complete: function () {
                    toggleTfa.prop("disabled", false);
                },
            });
        });
    }
});
