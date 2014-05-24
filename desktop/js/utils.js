
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

modifyWithoutSave = false;

$(function() {
    /*********************Gestion de l'heure********************************/
    setInterval(function() {
        var date = new Date();
        date.setTime(date.getTime() + clientServerDiffDatetime);
        var hour = date.getHours();
        var minute = date.getMinutes();
        var seconde = date.getSeconds();
        var horloge = (hour < 10) ? '0' + hour : hour;
        horloge += ':';
        horloge += (minute < 10) ? '0' + minute : minute;
        horloge += ':';
        horloge += (seconde < 10) ? '0' + seconde : seconde;
        $('#horloge').text(horloge);
    }, 1000);

    initTooltips();

    // Ajax Loading Screen
    $(document).ajaxStart(function() {
        $.showLoading();
    });
    $(document).ajaxStop(function() {
        $.hideLoading();
    });

    $.fn.modal.Constructor.prototype.enforceFocus = function() {
    };

    $('body').delegate(".modal", "show", function() {
        document.activeElement.blur();
        $(this).find(".modal-body :input:visible:first").focus();
    });

    /************************Help*************************/

    //Display report bug
    $("#md_reportBug").dialog({
        autoOpen: false,
        modal: true,
        width: 600,
        open: function() {
            $("body").css({overflow: 'hidden'})
        },
        beforeClose: function(event, ui) {
            $("body").css({overflow: 'inherit'})
        }
    });

    //Display help
    $("#md_pageHelp").dialog({
        autoOpen: false,
        modal: true,
        height: (jQuery(window).height() - 150),
        width: 1500,
        open: function() {
            if ((jQuery(window).width() - 50) < 1500) {
                $('#md_modal').dialog({width: jQuery(window).width() - 50});
            }
            $("body").css({overflow: 'hidden'})
        },
        beforeClose: function(event, ui) {
            $("body").css({overflow: 'inherit'})
        }
    });

    $("#md_modal").dialog({
        autoOpen: false,
        modal: true,
        height: (jQuery(window).height() - 150),
        width: 1500,
        position: {my: 'center', at: 'center', of: window},
        open: function() {
            if ((jQuery(window).width() - 50) < 1500) {
                $('#md_modal').dialog({width: jQuery(window).width() - 50});
            }
            $("body").css({overflow: 'hidden'});
        },
        beforeClose: function(event, ui) {
            $("body").css({overflow: 'inherit'})
        }
    });

    $("#md_modal2").dialog({
        autoOpen: false,
        modal: true,
        height: (jQuery(window).height() - 250),
        width: 1200,
        position: {my: 'center', at: 'center', of: window},
        open: function() {
            if ((jQuery(window).width() - 50) < 1500) {
                $('#md_modal2').dialog({width: jQuery(window).width() - 50});
            }
            $("body").css({overflow: 'hidden'})
        },
        beforeClose: function(event, ui) {
            $("body").css({overflow: 'inherit'})
        }
    });

    $('#bt_jeedomAbout').on('click', function() {
        $('#md_modal').load('index.php?v=d&modal=about').dialog('open');
    });

    /******************Gestion mode expert**********************/

    $('#bt_expertMode').on('click', function() {
        if ($(this).attr('state') == 1) {
            var value = {options: {expertMode: 0}};
            $(this).attr('state', 0);
            $(this).find('i').removeClass('fa-check-square-o').addClass('fa-square-o');
        } else {
            var value = {options: {expertMode: 1}};
            $(this).attr('state', 1);
            $(this).find('i').removeClass('fa-square-o').addClass('fa-check-square-o');
        }
        initExpertMode();
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "core/ajax/user.ajax.php", // url du fichier php
            data: {
                action: "saveUser",
                user: json_encode(value)
            },
            dataType: 'json',
            global: false,
            error: function(request, status, error) {
                handleAjaxError(request, status, error);
            },
            success: function(data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                    $('#div_alert').showAlert({message: data.result, level: 'danger'});
                    return;
                }
            }
        });
    });

    $('.bt_pluginUpdate').on('click', function() {
        $('#md_modal2').dialog({title: "{{Market Jeedom}}"});
        $('#md_modal2').load('index.php?v=d&modal=market.display&type=plugin&logicalId=' + $(this).attr('data-logicalId')).dialog('open');
    });

    $('body').delegate('.bt_pageHelp', 'click', function() {
        showHelpModal($(this).attr('data-name'), $(this).attr('data-plugin'));
    });

    $('body').delegate('.bt_reportBug', 'click', function() {
        $('#md_reportBug').load('index.php?v=d&modal=report.bug');
        $('#md_reportBug').dialog('open');
    });

    $('ul.dropdown-menu [data-toggle=dropdown]').on('mouseover', function(event) {
        // Avoid following the href location when clicking
        event.preventDefault();
        // Avoid having the menu to close when clicking
        event.stopPropagation();
        // If a menu is already open we close it
        $('ul.dropdown-menu [data-toggle=dropdown]').parent().removeClass('open');
        // opening the one you clicked on
        $(this).parent().addClass('open');
        var menu = $(this).parent().find("ul");
        var menupos = menu.offset();
        if ((menupos.left + menu.width()) + 30 > $(window).width()) {
            var newpos = -menu.width();
        } else {
            var newpos = $(this).parent().width();
        }
        menu.css({left: newpos});
    });

    $(window).bind('beforeunload', function(e) {
        if (modifyWithoutSave) {
            return '{{Attention vous quittez une page ayant des données modifiées non sauvegardé. Voulez-vous continuer ?}}';
        }
    });

    initTableSorter();
    initExpertMode();
    $.initTableFilter();
});

function initExpertMode() {
    if ($('#bt_expertMode').attr('state') == 1) {
        $('.expertModeDisable').attr('disabled', false);
        $('.expertModeHidden').show();
    } else {
        $('.expertModeDisable').attr('disabled', true);
        $('.expertModeHidden').hide();
    }
}

function initTableSorter() {
    $(".tablesorter").each(function() {
        var widgets = ['uitheme', 'filter', 'zebra', 'resizable'];
        if ($(this).hasClass('tablefixheader')) {
            widgets.push("stickyHeaders");
        }
        $(".tablesorter").tablesorter({
            theme: "bootstrap",
            widthFixed: true,
            headerTemplate: '{content} {icon}',
            widgets: widgets,
            widgetOptions: {
                filter_ignoreCase: true,
                filter_saveFilters: true,
                resizable: true,
                stickyHeaders_offset: $('header.navbar-fixed-top').height(),
                zebra: ["ui-widget-content even", "ui-state-default odd"],
            }
        });
    });
}

function showHelpModal(_name, _plugin) {
    if (init(_plugin) != '' && _plugin != undefined) {
        $('#div_helpWebsite').load('index.php?v=d&modal=help.website&page=doc_plugin_' + _plugin + '.php #primary', function() {
            if ($('#div_helpWebsite').find('.alert.alert-danger').length > 0 || $.trim($('#div_helpWebsite').text()) == '') {
                $('a[href=#div_helpSpe]').click();
                $('a[href=#div_helpWebsite]').hide();
            } else {
                $('a[href=#div_helpWebsite]').show();
                $('a[href=#div_helpWebsite]').click();
            }
        });
        $('#div_helpSpe').load('index.php?v=d&plugin=' + _plugin + '&modal=help.' + init(_name));
    } else {
        $('#div_helpWebsite').load('index.php?v=d&modal=help.website&page=doc_' + init(_name) + '.php #primary', function() {
            if ($('#div_helpWebsite').find('.alert.alert-danger').length > 0 || $.trim($('#div_helpWebsite').text()) == '') {
                $('a[href=#div_helpSpe]').click();
                $('a[href=#div_helpWebsite]').hide();
            } else {
                $('a[href=#div_helpWebsite]').show();
                $('a[href=#div_helpWebsite]').click();
            }
        });
        $('#div_helpSpe').load('index.php?v=d&modal=help.' + init(_name));
    }
    $('#md_pageHelp').dialog('open');
}

function refreshMessageNumber() {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/message.ajax.php", // url du fichier php
        data: {
            action: "nbMessage"
        },
        dataType: 'json',
        global: false,
        error: function(request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function(data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#span_nbMessage').html('<i class="fa fa-envelope icon-white"></i> ' + data.result + ' message(s)');
            $('#span_nbMessage').show();
        }
    });
}

function notify(_title, _text, _class_name, _cleanBefore) {
    if (_title == '' && _text == '') {
        return true;
    }
    if (init(_cleanBefore, false)) {
        $.gritter.removeAll();
    }
    if (isset(_class_name) != '') {
        $.gritter.add({
            title: _title,
            text: _text,
            class_name: _class_name
        });
    } else {
        $.gritter.add({
            title: _title,
            text: _text
        });
    }
}


jQuery.fn.findAtDepth = function(selector, maxDepth) {
    var depths = [], i;

    if (maxDepth > 0) {
        for (i = 1; i <= maxDepth; i++) {
            depths.push('> ' + new Array(i).join('* > ') + selector);
        }

        selector = depths.join(', ');
    }
    return this.find(selector);
};


function chooseIcon(callback) {
    if ($("#mod_selectIcon").length == 0) {
        $('body').append('<div id="mod_selectIcon" title="{{Choisissez votre icone}}" ></div>');

        $("#mod_selectIcon").dialog({
            autoOpen: false,
            modal: true,
            height: 600,
            width: 800
        });
        jQuery.ajaxSetup({async: false});
        $('#mod_selectIcon').load('index.php?v=d&modal=icon.selector');
        jQuery.ajaxSetup({async: true});
    }
    $("#mod_selectIcon").dialog('option', 'buttons', {
        "Annuler": function() {
            $(this).dialog("close");
        },
        "Valider": function() {
            var icon = $('.iconSelected').html();
            if (icon == undefined) {
                icon = '';
            }
            callback(icon);
            $(this).dialog('close');
        }
    });
    $('#mod_selectIcon').dialog('open');
}


function positionEqLogic() {
    var pas = 40;
    $('.eqLogic-widget').each(function() {
        var eqLogic = $(this)
        var maxHeight = 0;
        eqLogic.find('.cmd-widget').each(function() {
            if ($(this).height() > maxHeight) {
                maxHeight = $(this).height();
            }
            var statistiques = $(this).find('.statistiques');
            if (statistiques != undefined) {
                var left = ($(this).width() - statistiques.width()) / 2;
                statistiques.css('left', left);
            }
        });
        eqLogic.find('.cmd-widget').height(maxHeight);
        eqLogic.height((Math.ceil(eqLogic.height() / pas) * pas) - 6);
        eqLogic.width((Math.ceil(eqLogic.width() / pas) * pas) - 6);
        var verticalAlign = eqLogic.find('.verticalAlign');
        if (count(verticalAlign) > 0 && verticalAlign != undefined) {
            verticalAlign.css('position', 'relative');
            verticalAlign.css('top', ((eqLogic.height() - verticalAlign.height()) / 2) - 20);
            verticalAlign.css('left', (eqLogic.width() - verticalAlign.width()) / 2);
        }
    });


}