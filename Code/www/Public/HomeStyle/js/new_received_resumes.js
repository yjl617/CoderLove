$(function() {
    function offlineNotice() {
        var b, a = $(".resume_header2");
        for (b = 0; b < a.length; b++) a[b].onmouseover = function(a) {
            var b;
            a = a || window.event,
            b = a.srcElement || a.target,
            "EM" == b.tagName.toUpperCase() && $(this).find("span.offline_tips_con").css("display", "block")
        },
        a[b].onmouseout = function(a) {
            var b;
            a = a || window.event,
            b = a.srcElement || a.target,
            "EM" == b.tagName.toUpperCase() && $(this).find("span.offline_tips_con").css("display", "none")
        }
    }
    function GetDateStr(a) {
        var c, d, e, b = new Date;
        return b.setDate(b.getDate() + a),
        c = b.getFullYear(),
        d = b.getMonth() + 1 < 10 ? "0" + (b.getMonth() + 1) : b.getMonth() + 1,
        e = b.getDate() < 10 ? "0" + b.getDate() : b.getDate(),
        c + "-" + d + "-" + e
    }
    function getDays(a, b) {
        var c = "-",
        d = a.split(c),
        e = b.split(c),
        g = new Date(d[0], d[1] - 1, d[2]),
        h = new Date(e[0], e[1] - 1, e[2]),
        f = parseInt(Math.abs(g - h) / 1e3 / 60 / 60 / 24);
        return f
    }
    function holidaAajaxSubmit() {
        var a = $("#resubmitToken").val();
        $.ajax({
            type: "POST",
            url: ctx + "/holiday/addVacation.json",
            data: {
                fromDate: fromDate,
                endDate: endDate,
                resubmitToken: a
            },
            dataType: "json"
        }).done(function(a) { (null != a.resubmitToken || "" != a.resubmitToken) && $("#resubmitToken").val(a.resubmitToken),
            1 == a.state ? ($(".process_instruction a.holiday_how").css("display", "none"), $(".process_instruction .isVacation").append('<a class="has_holiday" href="javascript:;" id="hasVacation"><em></em>已设定休假</a>'), $("#confirm_htime").slideUp()) : alert(a.message)
        })
    }
    function holidayHow() {
        $(".process_instruction dl").css("marginBottom", "10px"),
        $("#select_holiday_time").slideDown()
    }
    function split(a) {
        return a.replace("；", ";"),
        a.split(/;\s*/)
    }
    function extractLast(a) {
        return split(a).pop()
    }
    function suggestEmail(a) {
        var b = "";
        $.ajax({
            type: "GET",
            async: !1,
            url: "forward",
            dataType: "json"
        }).done(function(a) {
            b = a.content
        }),
        $("#" + a).autocomplete({
            source: function(a, c) {
                c($.ui.autocomplete.filter(b, extractLast(a.term)))
            },
            focus: function() {
                return ! 1
            },
            select: function(a, b) {
                var c = split(this.value);
                return c.pop(),
                c.push(b.item.value),
                c.push(""),
                this.value = c.join("; "),
                !1
            }
        })
    }
    function noPrompt() {
        $.ajax({
            type: "post",
            url: ctx + "/refuse/clear.json",
            dataType: "json"
        }).done(function(a) {
            3 == a.state ? top.location.href = top.location.href: alert(a.message)
        })
    }
    function popQR() {
        $.ajax({
            url: ctx + "/mycenter/showQRCode",
            type: "GET"
        }).done(function(a) {
            a.success && ($("#weixinQR .qr img").attr("src", a.content), $.colorbox({
                inline: !0,
                href: $("#weixinQR"),
                title: "关注拉勾微信服务号"
            }))
        })
    }
    function highlight() {
        var a = $("#filterForm ul.resume_lists li").eq(0).height();
        $("#filterForm ul.resume_lists").prepend('<li class="borderHighlight"></li>'),
        $("#filterForm ul.resume_lists li.borderHighlight").css("height", a).fadeIn(400).delay(2500).fadeOut(400)
    }
    var select_holiday_timeBtn, todayDate, holiday_begintime, fromDate, endDate, today, noticeObj, refuseObj, flag, resume_look, resume_total;
    offlineNotice(),
    $(".isVacation").on("click", "a#holiday_how", holidayHow),
    select_holiday_timeBtn = $("#select_holiday_timeBtn"),
    select_holiday_timeBtn.click(function() {
        $("#select_holiday_time").slideUp(),
        $("#holiday_time").slideDown(),
        $(".isVacation").off("click", "a#holiday_how")
    }),
    todayDate = new Date,
    holiday_begintime = todayDate.getFullYear() + "-" + (todayDate.getMonth() + 1) + "-" + todayDate.getDate(),
    $("#holiday_begintime").val(GetDateStr(1)),
    $("#holiday_endtime").val(GetDateStr(8)),
    $("#holiday_begintimeBtn").click(function() {
        var begin_day = $("#holiday_begintime").val(),
        end_day = $("#holiday_endtime").val();
        $.ajax({
            type: "post",
            data: {
                fromDate: begin_day,
                endDate: end_day
            },
            url: ctx + "/holiday/preAddVacation.json",
            dataType: "json"
        }).done(function(result) {
            if (1 == result.state) {
                $("#confirm_htime").slideDown(),
                $("#holiday_time").slideUp();
                var vacation = eval("(" + result.content.data.vacation + ")");
                fromDate = vacation.fromDate,
                endDate = vacation.endDate,
                $(".cha").text(vacation.intervalDays),
                $(".begin_day").text(vacation.fromDateDotStr),
                $(".end_day").text(vacation.endDateDotStr)
            } else alert(result.message)
        })
    }),
    today = GetDateStr(0),
    $("#holiday_begintime").datepicker({
        dateFormat: "yy-mm-dd",
        minDate: 1,
        onClose: function(a) {
            $("#holiday_endtime").datepicker("option", "minDate", a),
            $("#holiday_endtime").val(GetDateStr(8))
        }
    }),
    $("#holiday_endtime").datepicker({
        dateFormat: "yy-mm-dd"
    }),
    $("#holiday_cancle").click(function() {
        $(".select_holiday_time").slideUp()
    }),
    $("#cancle_holiday_select").click(function() {
        $("#holiday_time").slideUp(),
        $("#holiday_begintime").val(GetDateStr(1)),
        $("#holiday_endtime").val(GetDateStr(8)),
        $(".isVacation").on("click", "a#holiday_how", holidayHow)
    }),
    $("#cancle_happy_decision").click(function() {
        $("#confirm_htime").slideUp(),
        $(".isVacation").on("click", "a#holiday_how", holidayHow),
        $("#holiday_begintime").val(GetDateStr(1)),
        $("#holiday_endtime").val(GetDateStr(8))
    }),
    $("#happy_decision").click(function() {
        holidaAajaxSubmit()
    }),
    $(".isVacation").on("click", "a#hasVacation",
    function() {
        $("#setVacation").slideToggle("200")
    }),
    $("#cancleVacation").click(function() {
        $.ajax({
            type: "POST",
            url: ctx + "/holiday/cancelVacation.json",
            dataType: "json"
        }).done(function(a) {
            1 == a.state ? ($("#setVacation").slideUp(), $(".process_instruction a.has_holiday").hide(), $(".process_instruction .isVacation").append('<a class="holiday_how"  href="javascript:;" id="holiday_how"><em></em>个人休假怎么办</a>'), $("#holiday_begintime").val(GetDateStr(1)), $("#holiday_endtime").val(GetDateStr(8))) : alert(a.message),
            $(".isVacation").on("click", "a#holiday_how", holidayHow)
        })
    }),
    $("#filter_btn2").click(function() {
        
        $(".filter_options").is(":visible") ? ($("#defaultCondition").val("true"), $(this).removeClass("filter_current"), $(".filter_options").hide().prev(".filter_actions").removeClass("btm")) : ($("#defaultCondition").val("false"), $(this).addClass("filter_current"), $(".filter_options").show().prev(".filter_actions").removeClass("btm")),
        $("#all_publish").find("ul").hide()
    }),
    $(".filter_options dd a").click(function() {
        $(this).addClass("current").siblings().removeClass("current");
        var a = $(this).attr("rel");
        $(this).siblings('input[type="hidden"]').val(a),
        $("#filterForm").submit()
    }),
    $("#all_publish").click(function(a) {
        var b = a || window.event;
        "filter_current" == $(this).attr("class") ? ($(this).find("ul").hide(), $(this).removeClass("filter_current"), $(".all_publish").removeClass("all_publish_current"), $(this).find("i").addClass("resume_triangle_two").removeClass("resume_triangle_one"), $(this).parents("div.filter_actions").css("z-index", "2")) : ($(this).find("ul").show(), $(this).addClass("filter_current"), $(".all_publish").addClass("all_publish_current"), $(this).find("i").addClass("resume_triangle_one").removeClass("resume_triangle_two"), $(this).parents("div.filter_actions").css("z-index", "3")),
        b.stopPropagation(),
        $("#filter_btn2").removeClass("filter_current"),
        $(".filter_options").hide()
    }),
    $(".all_publish li").click(function() {
        var a = $(this).text(),
        b = $(this).attr("id");
        $("#all_publish span").text(a),
        $("#all_publish span").attr("title", a),
        $('input[name="publisherId"]').val(b),
        $("#filterForm").submit()
    }),
    $("body").click(function() {
        "filter_current" == $("#all_publish").attr("class") && ($("#all_publish").find("ul").hide(), $("#all_publish").removeClass("filter_current"), $(".all_publish").removeClass("all_publish_current"), $("#all_publish i").addClass("resume_triangle_two"))
    }),
    $("#filter_btn2").click(function() {
        $(".filter_options").is(":visible") ? $(this).find("i").addClass("resume_triangle_one").removeClass("resume_triangle_two") : $(this).find("i").addClass("resume_triangle_two").removeClass("resume_triangle_one")
    }),
    $(".resume_notice").click(function() {
        var deliverId, linkEmail2, positionName, companyName;
        $('#noticeInterviewForm input[name="interTime"]').val(""),
        $("#notice_select_templete").removeClass("select_focus"),
        $("#noticeInterview .boxUpDown").hide(),
        deliverId = $(this).attr("data-deliverId"),
        linkEmail2 = $(this).attr("data-email"),
        positionName = $(this).attr("data-positionName"),
        companyName = $("#noticeCompanyName").val(),
        $('#noticeInterview input[name="deliverId"]').val(deliverId),
        $.ajax({
            type: "post",
            url : 'audition',
            dataType: "json",
            data : {
                id : deliverId,
                email : linkEmail2,
                job : positionName,
                comName : companyName,
            }
        }).done(function(result) {
            var template = eval("(" + result.content.data.templates[0].templateContent + ")");
            1 == result.state ? ($('#noticeInterview input[name="email"]').val(linkEmail2), $('#noticeInterview input[name="selectTemplate"]').val(template.alis), $('#noticeInterview input[name="name"]').val(result.content.name), $('#noticeInterview input[name="subject"]').val(companyName + "：" + positionName + "面试通知").removeClass("error").removeClass("valid"), $('#noticeInterview input[name="interAdd"]').val(template.interviewAddress).removeClass("error").removeClass("valid"), $('#noticeInterview input[name="linkPhone"]').val(template.linkPhone).removeClass("error").removeClass("valid"), $('#noticeInterview input[name="linkMan"]').val(template.linkMan), $('#noticeInterview textarea[name="content"]').val(template.content), $('#noticeInterview input[name="positionName"]').val(result.content.positionName), $('#noticeInterview input[name="companyName"]').val(result.content.companyName), $("#noticeInterview span.error").remove(), $.colorbox({
                inline: !0,
                href: $("#noticeInterview"),
                title: "通知面试"
            })) : alert(result.message)
        })
    }),
    noticeObj = {},
    $(".notice_templete").click(function() {
        $("#noticeInterviewForm span.error").hide(),
        $.ajax({
            type: "post",
            url: ctx + "/templates/getInterviewTemplates.json",
            dataType: "json"
        }).done(function(result) {
            var alisLength, str, i, templates;
            if (1 == result.state) {
                for (alisLength = eval("(" + result.content.data.templates.length + ")"), $("#notice_select_templete").parents("span").siblings("div.boxUpDown").show(), str = "", i = 0; alisLength > i; i++) templates = eval("(" + result.content.data.templates[i].templateContent + ")"),
                str += "<li>" + templates.alis + "</li>",
                noticeObj[templates.alis] = templates;
                $("#notice_select_templete").parents("span").siblings("div.boxUpDown").find("ul").html(str),
                $("#notice_select_templete").addClass("select_focus")
            } else alert(result.message)
        })
    }),
    $(".select_templete ul").on("click", "li",
    function() {
        var c, a = $(this).text(),
        b = $(this).parents("form#noticeInterviewForm").find("textarea").val();
        for (c in noticeObj) if (c == a) return b = noticeObj[a],
        $("#notice_select_templete").val(c),
        $('#noticeInterviewForm textarea[name="content"]').val(b.content),
        $('#noticeInterviewForm input[name="linkPhone"]').val(b.linkPhone),
        $('#noticeInterviewForm input[name="linkMan"]').val(b.linkMan),
        $('#noticeInterviewForm input[name="interAdd"]').val(b.interviewAddress),
        $("input#notice_select_templete").removeClass("select_focus"),
        $(this).parents("div.boxUpDown").hide(),
        !1
    }),
    $("#noticeInterview").click(function() {
        var a = $(".boxUpDown").hasClass("dn");
        a && ($(".boxUpDown").hide(), $("#notice_select_templete").removeClass("select_focus"))
    }),
    $("#noticeInterview .emailPreview").bind("click",
    function() {
        var j, k, a = $('#noticeInterview input[name="email"]').val(),
        b = $('#noticeInterviewForm input[name="email"]').val(),
        c = $('#noticeInterviewForm input[name="subject"]').val(),
        d = $('#noticeInterviewForm input[name="interTime"]').val(),
        e = $('#noticeInterviewForm input[name="interAdd"]').val(),
        f = $('#noticeInterviewForm input[name="linkMan"]').val(),
        g = $('#noticeInterviewForm input[name="linkPhone"]').val(),
        h = $('#noticeInterviewForm textarea[name="content"]').val();
        h = h.replace("/\r\n/g", "<br>"),
        $("#noticeInterviewPreview .f18").html(c),
        $("#noticeInterviewPreview .c9 span").html(b),
        j = a + "，您好：<br />您的简历已通过我们的筛选，很高兴通知您参加我们的面试。<br /><br />" + "面试时间：" + d + "<br />面试地点：" + e + "<br />",
        "" != f && (j += "联系人：" + f + "<br />"),
        j += "联系电话：" + g,
        h && (j += "<br /><br />" + h),
        $("#emailText").html(j),
        k = !1,
        ("" == c || $('#noticeInterviewForm input[name="subject"]').hasClass("error")) && (k = !0),
        ("" == d || $('#noticeInterviewForm input[name="interTime"]').hasClass("error")) && (k = !0),
        ("" == e || $('#noticeInterviewForm input[name="interAdd"]').hasClass("error")) && (k = !0),
        ("" == g || $('#noticeInterviewForm input[name="linkPhone"]').hasClass("error")) && (k = !0),
        $('#noticeInterviewForm textarea[name="content"]').hasClass("error") && (k = !0),
        k ? $("#noticeInterviewPreview .btn").hide() : $("#noticeInterviewPreview .btn").show(),
        $.colorbox({
            inline: !0,
            href: $("#noticeInterviewPreview"),
            title: "通知面试"
        })
    }),
    $("#noticeInterviewForm").validate({
        rules: {
            subject: {
                required: !0,
                maxlength: 200
            },
            interTime: {
                required: !0
            },
            interAdd: {
                required: !0,
                maxlength: 200
            },
            linkMan: {
                required: !1,
                rangelength: [2, 16]
            },
            linkPhone: {
                required: !0,
                isTel: !0,
                maxlength: 30
            },
            content: {
                required: !1,
                maxlength: 500
            }
        },
        messages: {
            subject: {
                required: "请输入你的主题",
                maxlength: "请输入200字符以内的主题"
            },
            interTime: {
                required: "请输入你的面试时间"
            },
            interAdd: {
                required: "请输入你的面试地点",
                maxlength: "请输入200字符以内的面试地点"
            },
            linkPhone: {
                required: "请输入你的联系电话",
                isTel: "请输入正确的手机号或座机号，座机格式如010-62555255或010-6255255-分机号，多个电话用英文逗号隔开",
                maxlength: "请输入30字以内的联系电话"
            },
            linkMan: {
                rangelength: "请输入2-16个字符的联系人"
            },
            content: {
                maxlength: "请输入500字符以内的补充内容"
            }
        },
        submitHandler: function(a) {
            var b = $('input[name="subject"]', a).val(),
            c = $('input[name="interTime"]', a).val(),
            d = $('input[name="interAdd"]', a).val(),
            e = $('input[name="linkMan"]', a).val(),
            f = $('input[name="linkPhone"]', a).val(),
            g = $('textarea[name="content"]', a).val(),
            h = $('input[name="deliverId"]', a).val(),
            i = $("#resubmitToken").val();
            $.ajax({
                type: "POST",
                data: {
                    title: b,
                    interviewTime: c,
                    interAdd: d,
                    linkMan: e,
                    linkPhone: f,
                    content: g,
                    deliverId: h,
                    resubmitToken: i
                },
                url: "sendAudition",
                dataType: "json"
            }).done(function(a) {
                null != a.resubmitToken && "" != a.resubmitToken && $("#resubmitToken").val(a.resubmitToken),
                1 == a.state ? $.colorbox({
                    inline: !0,
                    href: $("#noticeInterviewSuccess"),
                    title: "通知面试"
                }) : 500 == a.state ? $.colorbox({
                    inline: !0,
                    href: $("#statusChange"),
                    title: "简历状态变化"
                }) : alert(a.message)
            })
        }
    }),
    $("#noticeInterviewPreview .btn").bind("click",
    function() {
        $("#noticeInterviewForm").submit()
    }),
    $("#datetimepicker").datetimepicker({
        showMonthAfterYear: !0,
        controlType: "select",
        minDate: 0,
        hourMin: 7,
        hourMax: 23,
        stepMinute: 10,
        dateFormat: "yy-mm-dd"
    }),
    $("#datetimepicker").focus(function() {
        var a = $("#datetimepicker").val().length;
        0 != a && $("#datetimepicker").siblings("span").hide()
    }),
    refuseObj = {},
    $(".selectRefuse").click(function() {
        $("#refuseMailForm span.error").hide(),
        $.ajax({
            type: "post",
            url: ctx + "/templates/getRefuseTemplates.json",
            dataType: "json"
        }).done(function(result) {
            var alisLength, str, i;
            if (1 == result.state) {
                for (alisLength = result.content.data.templates.length, $("#refuse_select_templete").parents("span").siblings("div.boxUpDown").show(), str = "", i = 0; alisLength > i; i++) templates = result.content.data.templates[i],
                str += "<li>" + templates.alis + "</li>",
                refuseObj[templates.alis] = eval("(" + templates.templateContent + ")");
                $("#refuse_select_templete").parents("span").siblings("div.boxUpDown").find("ul").html(str),
                $("#refuse_select_templete").addClass("select_focus")
            } else alert(result.message)
        })
    }),
    $(".select_templete2 ul").on("click", "li",
    function(a) {
        var b, c;
        a.stopPropagation(),
        b = $(this).text();
        for (c in refuseObj) if (c == b) return content = refuseObj[b],
        $("#refuse_select_templete").val(c),
        $('#refuseMailForm textarea[name="content"]').val(content.content),
        $("input#refuse_select_templete").removeClass("select_focus"),
        $(this).parents("div.boxUpDown").hide(),
        !1
    }),
    $("#confirmRefuse").click(function() {
        var a = $(".boxUpDown").hasClass("dn");
        a && ($(".boxUpDown").hide(), $("#refuse_select_templete").removeClass("select_focus"))
    }),
    $("#refuseMailForm").validate({
        rules: {
            content: {
                required: !0,
                maxlength: 200
            }
        },
        messages: {
            content: {
                required: "请输入你的邮件内容",
                maxlength: "请输入200字符以内的邮件内容"
            }
        },
        submitHandler: function(a) {
            var b = $('textarea[name="content"]', a).val(),
            c = $('input[name="deliverId"]', a).val(),
            d = $("#resubmitToken").val();
            $.ajax({
                url: "sendNotPass",
                type: "POST",
                data: {
                    content: b,
                    deliverIds: c,
                    resubmitToken: d
                },
                dataType: "json"
            }).done(function(a) {
                null != a.resubmitToken && "" != a.resubmitToken && $("#resubmitToken").val(a.resubmitToken),
                5 == a.state ? $.colorbox({
                    inline: !0,
                    href: $("#refuseMailSuccess"),
                    title: "确认简历不合适"
                }) : 3 == a.state ? $.colorbox({
                    inline: !0,
                    href: $("#statusChange"),
                    title: "简历状态变化"
                }) : alert(a.message)
            })
        }
    }),
    $(".resume_refuse").bind("click",
    function() {
        $("#refuse_select_templete").removeClass("select_focus"),
        $("#refuseMailForm .boxUpDown").hide();
        var deliverId = $(this).attr("data-deliverId");
        $('#refuseMailForm input[name="deliverId"]').val(deliverId),
        $.ajax({
            url: 'notPass',
            dataType: "json",
            type: "POST"
        }).done(function(result) {
            var template = eval("(" + result.content.data.templates[0].templateContent + ")");
            1 == result.state && ($("#refuseMailForm input#refuse_select_templete").val(template.alis), $("#refuseMailForm textarea").val(template.content).removeClass("error").removeClass("valid"), $("#refuseMailForm span.error").remove()),
            $.colorbox({
                inline: !0,
                href: $("#confirmRefuse"),
                title: "确认简历不合适"
            })
        })
    }),
    $(document).click(function() {
        $(".send_notice_tips").hide()
    }),
    $(".click_bf").click(function(a) {
        a.stopPropagation();
        var b = $(this).find(".up_tip").attr("data-flag");
        $(".resume_lists li[data-flag = '" + b + "'] .send_notice .send_notice_tips").show()
    }),
    $("#mianshiYear").datetimepicker({
        showMonthAfterYear: !0,
        controlType: "select",
        hourMin: 7,
        hourMax: 23,
        stepMinute: 10,
        dateFormat: "yy-mm-dd"
    }),
    $(".has_notice").click(function(a) {
        $(".send_notice_tips").hide(),
        $('#hasOfflineNoticeForm input[name="noticeTime"]').val(""),
        $("#offlineDeliverId").val($(this).attr("data-deliverid")),
        $('#hasOfflineNoticeForm input[name="noticeTime"]').siblings("span.error").hide(),
        a.stopPropagation(),
        $(this).attr("data-deliverId"),
        $.colorbox({
            inline: !0,
            href: $("#hasOfflineNotice"),
            title: "标注为“已安排面试”"
        })
    }),
    $("#colorbox").on("click", "#cboxClose",
    function() {
        "hasOfflineNotice" == $(this).siblings("#cboxLoadedContent").children("div").attr("id") && $(".send_notice_tips").hide()
    }),
    $("#hasOfflineNoticeForm").validate({
        focusInvalid: !1,
        rules: {
            noticeTime: {
                required: !0
            }
        },
        messages: {
            noticeTime: {
                required: "请选择面试时间"
            }
        },
        submitHandler: function() {
            var a = $("#offlineDeliverId").val(),
            b = $('#hasOfflineNotice input[name="noticeTime"]').val();
            $.ajax({
                type: "post",
                url: ctx + "/interview/offline.json",
                data: {
                    relationId: a,
                    interviewTime: b
                },
                dataType: "json"
            }).done(function(a) {
                var b, c;
                1 == a.state ? (b = $("#offlineDeliverId").val(), $.colorbox.close(), $(".resume_lists li[data-flag ='" + b + "'] .send_notice").hide(), $(".resume_lists li[data-flag = '" + b + "'] .resume_fit_forward").show(), c = $("#noEmail_" + b).val(), "true" == c ? $(".resume_lists li[data-flag = '" + b + "'] .resume_header2").append('<span class="resume_feedback resume_publisher">线下安排面试，待确认</span><span class="dn offline_tips_con">求职者确认后，简历会进入已安排面试中，释放一个简历名额，超过7日求职者未确认，系统会处理为已确认</span><em class="offline_tips"></em>') : "false" == c && $(".resume_lists li[data-flag = '" + b + "'] .resume_header2").append('<span class="resume_feedback resume_publisher">线下安排面试，待确认</span><span class="dn offline_tips_con offline_tips_con2">求职者确认后，简历会进入已安排面试中，释放一个简历名额，超过7日求职者未确认，系统会处理为已确认</span><em class="offline_tips"></em>'), $(".resume_lists li[data-flag = '" + b + "'] .resume_header2 .checkbox").css("background", "#efefef"), $(".resume_lists li[data-flag = '" + b + "'] .resume_header2 .checkbox input[type='checkbox']").prop("disabled", "true")) : 500 == a.state ? $.colorbox({
                    inline: !0,
                    href: $("#statusChange"),
                    title: "简历状态变化"
                }) : alert(a.message)
            })
        }
    }),
    $("#hasOfflineNotice .cancel").click(function() {
        $.colorbox.close(),
        $(".send_notice_tips").hide(),
        $('#hasOfflineNoticeForm input[name="noticeTime"]').siblings("span.error").hide(),
        $('#hasOfflineNoticeForm input[name="noticeTime"]').val("")
    }),
    $("#resumeRefuseAll").click(function() {
        var b, c, a = !1;
        return $(".resume_lists li").each(function() {
            $(".checkbox input", this).prop("disabled") || (a = !0)
        }),
        a ? (b = new Array, c = $("#resubmitToken").val(), $(".resume_lists li").each(function() {
            $(this).find('input[type="checkbox"]').attr("checked") && b.push($(this).attr("data-flag"))
        }), b = b.join(","), "" == b ? (alert("请选择要标记的简历"), !1) : confirm("确定要标记选中的简历为不合适简历吗？确认后系统将自动发送拒绝信至用户邮箱") ? ($.ajax({
            type: "POST",
            url: ctx + "/refuse/hrRefuse.json",
            data: {
                deliverIds: b,
                resubmitToken: c
            },
            dataType: "json"
        }).done(function(a) {
            $("#resubmitToken").val(a.resubmitToken),
            5 == a.state ? top.location.href = top.location.href: alert(a.message)
        }), void 0) : !1) : ($(".filter_actions .checkbox").css("background", "#efefef"), $(".filter_actions .checkbox input").prop("disbaled", "true"), void 0)
    }),
    $(".filter_actions .checkbox input").bind("click",
    function() {
        return $(this).attr("checked") ? ($(this).removeAttr("checked"), $(this).siblings("i").fadeOut(200), $(".resume_lists li").each(function() {
            $(".checkbox i", this).fadeOut(200),
            $(".checkbox input", this).removeAttr("checked"),
            $(".checkbox", this).removeClass("checkhover")
        })) : ($(this).attr("checked", "checked"), $(this).siblings("i").fadeIn(200), $(".resume_lists li").each(function() {
            $(".checkbox input", this).prop("disabled") ? $(".checkbox i", this).fadeOut(200) : $(".checkbox i", this).fadeIn(200),
            $(".checkbox input", this).attr("checked", "checked"),
            $(".checkbox", this).removeClass("checkhover")
        })),
        $(this).parent().removeClass("checkhover"),
        !1
    }),
    $(".resume_lists .checkbox input").bind("click",
    function() {
        if ($(this).attr("checked")) {
            $(this).removeAttr("checked"),
            $(this).siblings("i").fadeOut(200);
            var a = !1;
            $(".filter_actions .checkbox input").removeAttr("checked").siblings("i").fadeOut(200),
            $(".resume_lists li").each(function() {
                $(".checkbox input", this).attr("checked") && (a = !0)
            }),
            a || $(".filter_actions .checkbox input").removeAttr("checked").siblings("i").fadeOut(200)
        } else $(this).attr("checked", "checked"),
        $(this).siblings("i").fadeIn(200);
        return $(this).parent().removeClass("checkhover"),
        !1
    }),
    $(".checkbox").hover(function() {
        $(this).children("input").attr("checked") || $(this).addClass("checkhover")
    },
    function() {
        $(this).children("input").attr("checked") || $(this).removeClass("checkhover")
    }),
    $(".resume_del").bind("click",
    function() {
        var a = $(this),
        b = a.parents("li").attr("data-flag"),
        c = $("#resubmitToken").val();
        return confirm("确定要删除该简历吗？") ? ($.ajax({
            type: "POST",
            url: ctx + "/refuse/delete.json",
            data: {
                deliverIds: b,
                resubmitToken: c
            },
            dataType: "json"
        }).done(function(a) {
            $("#resubmitToken").val(a.resubmitToken),
            3 == a.state ? top.location.href = top.location.href: alert(a.message)
        }), void 0) : !1
    }),
    $("#resumeDelAll").bind("click",
    function() {
        var a = new Array,
        b = $("#resubmitToken").val();
        return $(".resume_lists li").each(function() {
            $(this).find('input[type="checkbox"]').attr("checked") && a.push($(this).attr("data-flag"))
        }),
        a = a.join(","),
        0 == a.length ? (alert("请选择要删除的简历"), !1) : confirm("确定要删除选中的简历吗？") ? ($.ajax({
            type: "POST",
            url: ctx + "/refuse/delete.json",
            data: {
                deliverIds: a,
                resubmitToken: b
            },
            dataType: "json"
        }).done(function(a) {
            var b, c, d, e;
            $("#resubmitToken").val(a.resubmitToken),
            3 == a.state ? (b = parseInt($("#pageNo").val()), b > 1 ? ($("#pageNo").val(b - 1), c = $("#pageNo").val(), d = window.location.href, e = d.split("&")[6].split("=")[1], d.substr(d.length - 1) == e && (d = d.substr(0, d.length - 1) + c), window.location.href = d) : top.location.href = top.location.href) : alert(a.message)
        }), void 0) : !1
    }),
    $(".i_konw").click(function() {
        $.colorbox.close(),
        $("#guide_line").fadeOut("600")
    }),
    $("#colorbox").on("click", "#cboxClose",
    function() {
        "reached_limit" == $(this).siblings("#cboxLoadedContent").children("div").attr("id") && $("#guide_line").fadeOut("600")
    }),
    $(".resume_lists a.resume_forward").on("click", "dl",
    function() {
        return ! 1
    }),
    $("#forwardResumeForm").validate({
        rules: {
            recipients: {
                required: !0,
                moreEmail: !0,
                maxlength: 100
            },
            title: {
                required: !0,
                maxlength: 200
            },
            content: {
                required: !0,
                maxlength: 500
            }
        },
        messages: {
            recipients: {
                required: "请输入收件人的邮件地址",
                moreEmail: "请输入有效的收件人的邮件地址，最多2个，并用;隔开",
                maxlength: "请输入100字以内的邮箱地址"
            },
            title: {
                required: "请输入你的主题",
                maxlength: "请输入200字符以内的主题"
            },
            content: {
                required: "请输入你的正文",
                maxlength: "请输入500字符以内的正文"
            }
        },
        submitHandler: function(a) {
            var b = $('input[name="recipients"]', a).val(),
            c = $('input[name="title"]', a).val(),
            d = $('textarea[name="content"]', a).val(),
            e = $('input[name="deliverId"]', a).val(),
            f = $("#resubmitToken").val();
            $.ajax({
                type: "POST",
                url: "sendForward",
                data: {
                    recipients: b,
                    title: c,
                    content: d,
                    deliverId: e,
                    resubmitToken: f
                },
                dataType: "json"
            }).done(function(a) {
                null != a.resubmitToken && "" != a.resubmitToken && $("#resubmitToken").val(a.resubmitToken),
                1 == a.state ? $.colorbox({
                    inline: !0,
                    href: $("#forwardResumeSuccess"),
                    title: "转发简历"
                }) : ($("#forwardResumeError").html(a.msg).addClass("error"), $("#forwardResumeForm .beError").html(a.msg).show())
            })
        }
    }),
    $(".resume_forward").bind("click",
    function() {
        var a, b, c, d, e, f;
        $(this).children("dl").hide(),
        a = $(this).attr("data-positionName"),
        b = $(this).attr("data-resumeKey"),
        c = $(this).attr("data-positionId"),
        d = $(this).attr("data-deliverId"),
        e = "（简历来自拉勾）" + $(this).attr("data-positionName") + "：" + $(this).attr("data-resumeName"),
        f = "以下是应聘“" + $(this).attr("data-positionName") + "”的简历。我已查阅，请您评估一下。 若觉合适，我们将安排面试，谢谢！",
        $('#forwardResumeForm input[name="positionName"]').val(a),
        $('#forwardResumeForm input[name="resumeKey"]').val(b),
        $('#forwardResumeForm input[name="positionId"]').val(c),
        $('#forwardResumeForm input[name="deliverId"]').val(d),
        $('#forwardResumeForm input[name="title"]').val(e).removeClass("error").removeClass("valid"),
        $('#forwardResumeForm textarea[name="content"]').val(f).removeClass("error").removeClass("valid"),
        $("#forwardResumeForm span.error").remove(),
        $("#recipients").val(""),
        placeholderFn(),
        suggestEmail("recipients"),
        $.colorbox({
            inline: !0,
            href: $("#forwardResume"),
            title: "转发简历"
        })
    }),
    $(".resume_lists li  a.resume_forward").hover(function() {
        var a, b, c;
        $(this).css("z-index", "6"),
        a = $(this),
        b = a.attr("data-forwardCount"),
        c = a.attr("data-deliverId"),
        0 != b && (1 == a.find("dl").length ? a.find("dl").show() : $.ajax({
            url: ctx + "/forward/showForwardEmails.json",
            type: "POST",
            data: {
                deliverId: c
            },
            dataType: "json"
        }).done(function(b) {
            var c, d;
            if (1 == b.state) {
                for (c = "<dl><dt>已转发给：</dt><dd><ul>", d = 0; d < b.content.data.emails.length; d++) c += "<li>" + b.content.data.emails[d] + "</li>";
                c += "</ul></dd></dl>",
                a.append(c)
            } else alert(b.message)
        }))
    },
    function() {
        $(this).find("dl").hide()
    }),
    $(".notice_noprompt").click(function() {
        $("#noPrompt").prop("checked") ? ($(".notice_noprompt em").css("backgroundPosition", "0px 0px"), $("#noPrompt").prop("checked", !1), $(".lp_agreeNotice").next("span").show()) : ($("#noPrompt").prop("checked", !0), $(".notice_noprompt").next("span").hide(), $(".notice_noprompt em").css("backgroundPosition", "0px -12px"))
    }),
    $("#resume_empty").click(function() {
        $.cookie("noNotice2") ? noPrompt() : ($.colorbox({
            inline: !0,
            href: $("#empty_resume"),
            title: "清空“不合适”中的简历"
        }), $("#empty_resume #confirmEmpty").click(function() {
            noPrompt(),
            $("#noPrompt").prop("checked") && $.cookie("noNotice2", "noticeValue", {
                expires: 7
            })
        }))
    }),
    $("#colorbox").on("click", "#cboxClose",
    function() {
        "forwardResumeSuccess" == $(this).siblings("#cboxLoadedContent").children("div").attr("id") && (top.location.href = top.location.href)
    }),
    $("#colorbox").on("click", "#cboxClose",
    function() {
        "noticeInterview" == $(this).siblings("#cboxLoadedContent").children("div").attr("id") && $('#noticeInterview input[name="interTime"]').val("")
    }),
    $("#colorbox").on("click", "#cboxClose",
    function() {
        "hasOfflineNotice" == $(this).siblings("#cboxLoadedContent").children("div").attr("id") && $('#hasOfflineNotice input[name="noticeTime"]').val("")
    }),
    $("#forwardResumeSuccess .btn,#noticeInterviewSuccess .btn,#refuseMailSuccess .btn").bind("click",
    function() {
        $.colorbox.close(),
        top.location.href = top.location.href
    }),
    $(".emial_cancel").bind("click",
    function() {
        $.colorbox.close(),
        parent.jQuery.colorbox.close()
    }),
    $(".resume_download").bind("click",
    function() {
        $.colorbox({
            inline: !0,
            href: $("#downloadOnlineResume"),
            title: "下载简历"
        })
    }),
    $("#weixinQR .btn_s,#weixinQR .qr_cancel").click(function() {
        var a = new Date;
        $.cookie("showQRCode", a.getDate(), {
            expired: 180
        }),
        $.colorbox.close(),
        parent.jQuery.colorbox.close()
    }),
    $(".hide_contact_content a,.hide_contact_border .un_delete").click(function() {
        $(".hide_contact,.un_star1").fadeOut(300),
        $(".un_star2,.contact_number").fadeIn(300)
    }),
    $(".contact_number_content a,.contact_number_border .un_delete").click(function() {
        $(".un_star2,.contact_number").fadeOut(300),
        $(".un_star3,.deal_know").fadeIn(300)
    }),
    $(".deal_know_border .un_delete,.deal_know_content a").click(function() {
        $(".un_star3,.deal_know,#mask").fadeOut(300)
    }),
    $(".look_resume_link span").bind("click",
    function() {
        var a = $(this).attr("data-deliverId");
        $.ajax({
            type: "POST",
            url : 'look',
            data: {
                id: a
            },
            dataType: "json"
        }).done(function(b) {
            var c, d, e, f, g, h, i, j;
            1 == b.state ? (c = $("#pageType").val(), $(".resume_lists li[data-flag = '" + a + "'] .look_resume_link").empty(), $(".resume_lists li[data-flag = '" + a + "'] .look_resume_link").append('<a href="javascript:;" class="phone">' + b.content.data.phone + '</a><a href="javascript:;" class="resume_color phone" title="' + b.content.data.email + '">' + b.content.data.email + "</a>"), $(".resume_lists li[data-flag = '" + a + "'] .look_resume_link").css("padding", "20px 0px"), $(".resume_lists li[data-flag = '" + a + "'] .send_notice .resume_notice").attr("data-email", b.content.data.email), "1" == c && ($(".resume_lists li[data-flag = '" + a + "'] .resume_link").append('<div class="resume_tips dn">刷新页面后，该简历会移入待处理</div>'), d = $(".resume_lists li[data-flag = '" + a + "'] .resume_tips"), d.show(), setTimeout(function() {
                d.fadeOut("200")
            },
            3e3)), e = $("<b>").text("+1"), $(".resume_number").append(e), e.animate({
                top: "-30px",
                opacity: "0"
            },
            1e3,
            function() {
                e.remove()
            }), f = $(".resume_total").text(), g = $(".resume_number .resume_look").text(), g++, $(".resume_number .resume_look").text(g), .75 == g / f || g / f > .75 ? ($(".resume_number").show(), "true" == flag ? $(".resume_number").addClass("redHasWhiteBg") : $(".resume_number").addClass("redNoWhiteBg"), $(".resume_number").hover(function() {
                "true" == flag ? $(".resume_number").removeClass("redNoWhiteBg").addClass("redHasWhiteBg") : $(".resume_number").removeClass("redNoWhiteBg").addClass("redHasWhiteBg")
            },
            function() {
                "true" == flag ? $(".resume_number").removeClass("redNoWhiteBg").addClass("redHasWhiteBg").show() : $(".resume_number").removeClass("redHasWhiteBg").addClass("redNoWhiteBg").show()
            })) : g == f && (g = f), $(".resume_lists li[data-flag = '" + a + "'] .look_resume_fit").hide(), $(".resume_lists li[data-flag = '" + a + "'] .resume_fit_forward").hide(), $(".resume_lists li[data-flag = '" + a + "'] .send_notice").show()) : 301 == b.state ? (c = $("#pageType").val(), "1" == c ? $.colorbox({
                inline: !0,
                href: $("#autoFilter_reached_limit"),
                title: "待定简历到达上限"
            }) : (h = $("#publisherName" + a).val(), void 0 != h && "" != h ? (i = h.split("@")[0], $("#big_reached_limit").find("span").html(i), $.colorbox({
                inline: !0,
                href: $("#big_reached_limit"),
                title: "待定简历到达上限"
            })) : ($("#guide_line").fadeIn("600"), j = $(document).scrollTop(), $("body").css("position", "relative"), $("#guide_line").css({
                position: "absolute",
                top: 300 + j,
                left: "950px",
                "z-index": "99999"
            }), $.colorbox({
                inline: !0,
                href: $("#reached_limit"),
                title: "待定简历到达上限"
            })))) : $.colorbox({
                inline: !0,
                href: $("#statusChange"),
                title: "简历状态变化"
            })
        })
    }),
    $("#reached_limit .btn").click(function() {
        $.colorbox.close()
    }),
    $("#statusChange .btn").click(function() {
        top.location.href = top.location.href
    }),
    $("#colorbox").on("click", "#cboxClose",
    function() {
        "statusChange" == $(this).siblings("#cboxLoadedContent").children("div").attr("id") && (top.location.href = top.location.href)
    }),
    flag = $("#showContract").val(),
    resume_look = $(".resume_look").text(),
    resume_total = $(".resume_total").text(),
    .75 == resume_look / resume_total || resume_look / resume_total > .75 ? ($(".resume_number").show(), "true" == flag ? $(".resume_number").addClass("redHasWhiteBg") : $(".resume_number").addClass("redNoWhiteBg"), $(".resume_number").hover(function() {
        "true" == flag ? $(".resume_number").removeClass("redNoWhiteBg").addClass("redHasWhiteBg") : $(".resume_number").removeClass("redNoWhiteBg").addClass("redHasWhiteBg")
    },
    function() {
        "true" == flag ? $(".resume_number").removeClass("redNoWhiteBg").addClass("redHasWhiteBg").show() : $(".resume_number").removeClass("redHasWhiteBg").addClass("redNoWhiteBg").show()
    })) : ($(".resume_number").show(), "true" == flag ? $(".resume_number").addClass("hasWhiteBg") : $(".resume_number").addClass("noWhiteBg").show(), $(".resume_number").hover(function() {
        "true" == flag ? $(".resume_number").removeClass("noWhiteBg").addClass("hasWhiteBg") : $(".resume_number").removeClass("noWhiteBg").addClass("hasWhiteBg").show()
    },
    function() {
        "true" == flag ? $(".resume_number").removeClass("noWhiteBg").addClass("hasWhiteBg").show() : $(".resume_number").removeClass("hasWhiteBg").addClass("noWhiteBg").show()
    })),
    $(".resume_number").click(function() {
        var a = $("#showContract").val();
        $("#showContract").val("false" == a),
        $("#filterForm").submit()
    }),
    $(".resume_feedback em").hover(function() {
        $(this).find("span").show()
    },
    function() {
        $(this).find("span").hide()
    }),
    $(".click_af").click(function() {
        $(this).parents("div.send_notice_tips").hide()
    }),
    $(".send_notice_tips div").hover(function() {
        $(this).addClass("acticeHover"),
        $(this).find(".send_interview").addClass("activeHover"),
        $(this).find(".dw_tip").addClass("emActive")
    },
    function() {
        $(this).removeClass("acticeHover"),
        $(this).find(".send_interview").removeClass("activeHover"),
        $(this).find(".dw_tip").removeClass("emActive")
    }),
    function() {
        $(function() {
            var a = 0;
            $("#resume_select").bind("click",
            function() {
                1 == a ? ($(this).find("em").css("transform", "rotate(0deg)"), $(".resume_notice_hide").animate({
                    top: "-13px"
                },
                "normal"), a = 0) : ($(this).find("em").css("transform", "rotate(180deg)"), $(".resume_notice_hide").animate({
                    top: "-49px"
                },
                "normal"), a++)
            })
        })
    } ()
});