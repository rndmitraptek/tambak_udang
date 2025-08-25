// Added Arfiansyah Pulungan at 2020-02-05
$(document).ready(function() {
    $(document).on("click", ".direct-other-apps", function() {
        var key = $(this).attr("data-key"),
            wnd = $("<div />", {
                id: "container-other-apps",
                style: "position: fixed; top: 0; left: 0; right: 0; width: 100%; height: 100%; z-index: 1999; background-color: white;"
            }),
            a = $("<div />", {
                style: "height: 43.5px; width: 100%; border-bottom: 2px solid #cccccc; background-color: #efefef;"
            }),
            a_1 = $("<div />", {
                class: "row"
            }),
            a_1_1 = $("<div />", {
                class: "col-sm-6 col-md-6 text-left"
            }),
            a_1_1_1 = $("<p />", {
                style: "font-size: 16px; font-style: italic;"
            })
            .html("&nbsp;"),
            a_1_2 = $("<div />", {
                class: "col-sm-6 col-md-6 text-right p-2"
            }),
            a_1_2_1 = $("<button />", {
                class: "btn btn-sm btn-primary btn-close-other-apps mr-4"
            }),
            a_1_2_1_1 = $("<i />", {
                class: "fa fa-arrow-left mr-2"
            }),
            a_1_2_1_2 = $("<span />").html("Back to Transys"),
            b = $("<div />", {
                style: "height: calc(100% - 43.5px); width: 100%;"
            }),
            b_1 = $("<object />", {
                type: "text/html",
                width: "100%",
                height: "100%"
            })
            .attr("data", key + "?token=" + _token + "&signature=" + _signature);

        a_1_1.append(a_1_1_1);
        a_1_2.append(a_1_2_1.append(a_1_2_1_1).append(a_1_2_1_2));
        a_1.append(a_1_1).append(a_1_2);

        a.append(a_1);
        b.append(b_1);

        wnd.append(a).append(b);

        $("body").append(wnd);
    });

    $(document).on("click", ".btn-close-other-apps", function() {
        $(document).find("#container-other-apps").remove();
    });

    $(document).on("click", ".link-download", function(e) {
        var that = $(this),
            key = that.attr("data-key"),
            filename = that.attr("data-filename");

        e.preventDefault();

        fetch(_base_url + "ticket/download/myfile/" + key)
            .then(response => response.blob())
            .then(blob => {
                var url = window.URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();

                loading(false);
            });
    });

    checkAuth();
});

function checkAuth() {
    var url = _base_url + "auth/login/check_token?token=" + _token + "&signature=" + _signature;

    $.ajax({
        url: url,
        type: "GET",
        cache: false,
        dataType: "JSON",
        success: function(json) {
            if (!json.success) {
                console.warn("Your token for this session has expired.");

                window.location = _base_url + "auth/login";
            }
        },
        error: function(e) {
            console.error("Failed request: " + url);
        }
    });
}
// End Added ====================

function base_url() {
    var pathparts = location.pathname.split('/');
    if (location.host == 'localhost') {
        var url = location.origin + '/' + pathparts[1].trim('/') + '/'; // http://localhost/myproject/
    } else {
        var url = location.origin; // http://stackoverflow.com
    }
    return url;
}

function modal($options) {
    var idModal = $options.id || "myModal",
        wModal = $options.width || "md",
        wTitle = $options.title || "Modal",
        wrapper = $("<div />", {
            class: "modal modal-auto fade",
            id: idModal,
            role: "dialog"
        }),
        container = $("<div />", {
            class: "modal-dialog modal-" + wModal,
            role: "document"
        }),
        a = $("<div />", {
            class: "modal-content"
        }),
        a1 = $("<div />", {
            class: "modal-header"
        }),
        a11 = $("<h5 />", {
            class: "modal-title"
        }).html(wTitle),
        a12 = $("<button />", {
            class: "close",
            type: "button"
        }).attr("data-dismiss", "modal").attr("aria-label", "Close"),
        a121 = $("<span />").attr("aria-hidden", "true").html("&times"),
        a2 = $("<div />", {
            class: "modal-body"
        }),
        a3 = $("<div />", {
            class: "modal-footer"
        }),
        a31 = $("<button />", {
            class: "btn btn-secondary",
            type: "button"
        }).attr("data-dismiss", "modal").html("Close"),
        a32 = $("<button />", {
            class: "btn btn-primary",
            type: "button"
        }).html("Save changes");

    a12.append(a121);
    a1.append(a11).append(a12);
    a3.append(a31).append(a32);
    a.append(a1).append(a2);

    if (typeof $options.footer != "undefined") {
        if ($options.footer) {
            a.append(a3);
        }
    }

    container.append(a);
    wrapper.append(container);

    $(".modal-auto").remove();
    $("body").append(wrapper);

    if ($options.content) {
        a2.load($options.content);
    }

    $("#" + idModal).modal("show");
}

function loading(bool) {
    if (!bool) {
        $(document).find(".loading").remove();
    } else {
        var a = $("<div />", {
                class: "loading"
            }),
            b = $("<div />", {
                class: "loading-container"
            }),
            c = $("<div />", {
                class: "loading-content"
            }),
            d = $("<img />", {
                src: _base_url + "assets/images/loading.gif"
            });

        if ($(document).find(".loading").length == 0) {
            $(document).find(".loading").remove();
        }

        $("body").append(a.append(b.append(c.append(d))));
    }
}

function uniqid() {
    var n = Math.floor(Math.random() * 11);
    var k = Math.floor(Math.random() * 1000000);
    var m = String.fromCharCode(n) + k;

    return m.substr(1, m.length);
}

function colVis_set(colVis_table, $dt_colVis) {
    // init colVis
    var colvis = new $.fn.dataTable.ColVis(colVis_table, {
        buttonText: 'Select columns',
        exclude: [0],
        restore: "Restore",
        showAll: "Show all",
        showNone: "Show none"
    });

    // custom colVis elements
    var _colVis_button = $(colvis.dom.button).off('click').attr('class', 'md-btn md-btn-colVis');
    var _colVis_wrapper = $('<div class="uk-button-dropdown uk-text-left" data-uk-dropdown="{mode:\'click\'}"/>').append(_colVis_button);
    var _colVis_wrapper_outer = $('<div class="md-colVis uk-text-right"/>').append(_colVis_wrapper);
    var _colVis_collection = $(colvis.dom.collection);

    // Modify colVis collection
    $(_colVis_collection)
        .attr({
            'class': 'md-list-inputs',
            'style': ''
        })
        .find('input')
        .each(function(index) {
            var inputClone = $(this).clone().hide();
            $(this).attr({
                'class': 'data-md-icheck',
                'id': 'col_' + index
            }).css({
                'float': 'left'
            }).before(inputClone)
        })
        .end()
        .find('span').unwrap()
        .each(function() {
            var thisText = $(this).text();
            var thisInputId = $(this).prev('input').attr('id');
            $(this)
                .after('<label for="' + thisInputId + '">' + thisText + '</label>')
                .end()
        })
        .remove();

    // append collection to collection wrapper
    var _colVis_collection_wrapper = $('<div class="uk-dropdown uk-dropdown-flip"/>').append(_colVis_collection);

    // append collection wrapper to colVis wrapper
    _colVis_wrapper
        .append(_colVis_collection_wrapper);

    // insert colVis elements before datatable header
    $dt_colVis.closest('.dt-uikit').find('.dt-uikit-header').before(_colVis_wrapper_outer);

    // initialize styled checkboxes in dropdown
    altair_md.checkbox_radio();

    // custom events
    $dt_colVis.closest('.dt-uikit').find('.md-colVis .data-md-icheck').on('ifClicked', function() {
        $(this).closest('li').click();
    });

    $dt_colVis.closest('.dt-uikit').find('.md-colVis .ColVis_ShowAll,.md-colVis .ColVis_Restore').on('click', function() {
        $(this).closest('.uk-dropdown').find('.data-md-icheck').prop('checked', true).iCheck('update')
    });

    $dt_colVis.closest('.dt-uikit').find('.md-colVis .ColVis_ShowNone').on('click', function() {
        $(this).closest('.uk-dropdown').find('.data-md-icheck').prop('checked', false).iCheck('update')
    });
}

function cmbselect(curl, elemen) {
    $.ajax({
        url: curl,
        dataType: "json",
        success: function(data) {
            $v = elemen.selectize({
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                options: data,
                create: false,
                onChange: function(value) {}
            });

            cmb = $v[0].selectize;
        }
    });
}

function convertDate(date) {
    var yyyy = date.getFullYear().toString();
    var mm = (date.getMonth() + 1).toString();
    var dd = date.getDate().toString();

    var mmChars = mm.split('');
    var ddChars = dd.split('');

    return yyyy + '-' + (mmChars[1] ? mm : "0" + mmChars[0]) + '-' + (ddChars[1] ? dd : "0" + ddChars[0]);
}

function load_processing(message = 'Please Wait..') {
    swal({
        title: "Processing...!",
        text: '<div class="md-preloader md-preloader-success"><svg xmlns="http:www.w3.org/2000/svg" version="1.1" height="80" width="80" viewbox="0 0 75 75"><circle cx="37.5" cy="37.5" r="33.5" stroke-width="4"/></svg></div><p id="textload" style="display: block;"></p>',
        html: true,
        showConfirmButton: false
    });
    $('#textload').html(message);
}

function extractNumber(obj, decimalPlaces, allowNegative) {
    var temp = obj.value;

    // avoid changing things if already formatted correctly
    var reg0Str = '[0-9]*';
    if (decimalPlaces > 0) {
        reg0Str += '\\.?[0-9]{0,' + decimalPlaces + '}';
    } else if (decimalPlaces < 0) {
        reg0Str += '\\.?[0-9]*';
    }
    reg0Str = allowNegative ? '^-?' + reg0Str : '^' + reg0Str;
    reg0Str = reg0Str + '$';
    var reg0 = new RegExp(reg0Str);
    if (reg0.test(temp)) return true;

    // first replace all non numbers
    var reg1Str = '[^0-9' + (decimalPlaces != 0 ? '.' : '') + (allowNegative ? '-' : '') + ']';
    var reg1 = new RegExp(reg1Str, 'g');
    temp = temp.replace(reg1, '');

    if (allowNegative) {
        // replace extra negative
        var hasNegative = temp.length > 0 && temp.charAt(0) == '-';
        var reg2 = /-/g;
        temp = temp.replace(reg2, '');
        if (hasNegative) temp = '-' + temp;
    }

    if (decimalPlaces != 0) {
        var reg3 = /\./g;
        var reg3Array = reg3.exec(temp);
        if (reg3Array != null) {
            // keep only first occurrence of .
            //  and the number of places specified by decimalPlaces or the entire string if decimalPlaces < 0
            var reg3Right = temp.substring(reg3Array.index + reg3Array[0].length);
            reg3Right = reg3Right.replace(reg3, '');
            reg3Right = decimalPlaces > 0 ? reg3Right.substring(0, decimalPlaces) : reg3Right;
            temp = temp.substring(0, reg3Array.index) + '.' + reg3Right;
        }
    }

    obj.value = temp;
}

function blockNonNumbers(obj, e, allowDecimal, allowNegative) {
    var key;
    var isCtrl = false;
    var keychar;
    var reg;

    if (window.event) {
        key = e.keyCode;
        isCtrl = window.event.ctrlKey
    } else if (e.which) {
        key = e.which;
        isCtrl = e.ctrlKey;
    }

    if (isNaN(key)) return true;

    keychar = String.fromCharCode(key);

    // check for backspace or delete, or if Ctrl was pressed
    if (key == 8 || isCtrl) {
        return true;
    }

    reg = /\d/;
    var isFirstN = allowNegative ? keychar == '-' && obj.value.indexOf('-') == -1 : false;
    var isFirstD = allowDecimal ? keychar == '.' && obj.value.indexOf('.') == -1 : false;

    return isFirstN || isFirstD || reg.test(keychar);
}


function format_tanggal_indonesia(tanggal) {
    var hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum&#36;at', 'Sabtu'];
    var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    var day = tanggal.split('-');
    var thn = day[0];
    var bln = day[1] - 1;
    var tgl = day[2];
    // var hr=Date(tanggal).getDay();
    if (bln == -1) {
        return "";
    } else {
        return tgl + ' ' + bulan[bln] + ' ' + thn;
    }
}

function bulan(tanggal) {
    var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    var day = tanggal.split('-');
    var bln = day[1] - 1;
    return bulan[bln];
}

function cetakTri(vtext, url) {
    $("#f").empty();
    inputs = '<input type="hidden" name="text" value="' + vtext + '" />';
    $("#f").append('<form action="' + url + '" method="post" id="prin" target="_blank">' + inputs + '</form>');
    $("#prin").submit();
}

function cetakTriGet(vtext, url) {
    $("#f").empty();
    inputs = '<input type="hidden" name="text" value="' + vtext + '" />';
    $("#f").append('<form action="' + url + '" method="get" id="prin" target="_blank">' + inputs + '</form>');
    $("#prin").submit();
}

function cetakPrev(valxx, curl, id) {
    $(id).html('<center><div class="proses_loader"></div><br>Prosesing...!</center>');
    $.ajax({
        url: curl,
        type: "POST",
        data: ({ text: valxx }),
        dataType: "JSON",
        success: function(data) {
            $(id).html(data.isi);
        }
    });
}

function astardelete(id = '', nmtabel = '', keytabel = '', vurl = '') {
    swal({
            title: "Are you sure?",
            text: "You will not be able to recover this data!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        },
        function() {
            swal({ title: "Prosesing...!", text: '<center><div class="proses_loader"></div></center>', html: true, showConfirmButton: false });
            $.ajax({
                url: vurl,
                type: "POST",
                data: ({ cid: id, cnmtabel: nmtabel, ckeytabel: keytabel }),
                dataType: "JSON",
                success: function(data) {
                    reload_table();
                    swal("Deleted!", "Your data has been deleted.", "success");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    swal("Oops... Something went wrong!", "Please Call IT Programer!", "error");
                }
            });
        });
}

function number_format(number, decimals, dec_point, thousands_sep) {
    // Formats a number with grouped thousands
    //
    // version: 906.1806
    // discuss at: http://phpjs.org/functions/number_format
    // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     bugfix by: Michael White (http://getsprink.com)
    // +     bugfix by: Benjamin Lupton
    // +     bugfix by: Allan Jensen (http://www.winternet.no)
    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +     bugfix by: Howard Yeend
    // +    revised by: Luke Smith (http://lucassmith.name)
    // +     bugfix by: Diogo Resende
    // +     bugfix by: Rival
    // +     input by: Kheang Hok Chin (http://www.distantia.ca/)
    // +     improved by: davook
    // +     improved by: Brett Zamir (http://brett-zamir.me)
    // +     input by: Jay Klehr
    // +     improved by: Brett Zamir (http://brett-zamir.me)
    // +     input by: Amir Habibi (http://www.residence-mixte.com/)
    // +     bugfix by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: number_format(1234.56);
    // *     returns 1: '1,235'
    // *     example 2: number_format(1234.56, 2, ',', ' ');
    // *     returns 2: '1 234,56'
    // *     example 3: number_format(1234.5678, 2, '.', '');
    // *     returns 3: '1234.57'
    // *     example 4: number_format(67, 2, ',', '.');
    // *     returns 4: '67,00'
    // *     example 5: number_format(1000);
    // *     returns 5: '1,000'
    // *     example 6: number_format(67.311, 2);
    // *     returns 6: '67.31'
    // *     example 7: number_format(1000.55, 1);
    // *     returns 7: '1,000.6'
    // *     example 8: number_format(67000, 5, ',', '.');
    // *     returns 8: '67.000,00000'
    // *     example 9: number_format(0.9, 0);
    // *     returns 9: '1'
    // *     example 10: number_format('1.20', 2);
    // *     returns 10: '1.20'
    // *     example 11: number_format('1.20', 4);
    // *     returns 11: '1.2000'
    // *     example 12: number_format('1.2000', 3);
    // *     returns 12: '1.200'
    var n = number,
        prec = decimals;

    var toFixedFix = function(n, prec) {
        var k = Math.pow(10, prec);
        return (Math.round(n * k) / k).toString();
    };

    n = !isFinite(+n) ? 0 : +n;
    prec = !isFinite(+prec) ? 0 : Math.abs(prec);
    var sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
    var dec = (typeof dec_point === 'undefined') ? '.' : dec_point;

    var s = (prec > 0) ? toFixedFix(n, prec) : toFixedFix(Math.round(n), prec); //fix for IE parseFloat(0.55).toFixed(0) = 0;

    var abs = toFixedFix(Math.abs(n), prec);
    var _, i;

    if (abs >= 1000) {
        _ = abs.split(/\D/);
        i = _[0].length % 3 || 3;

        _[0] = s.slice(0, i + (n < 0)) +
            _[0].slice(i).replace(/(\d{3})/g, sep + '$1');
        s = _.join(dec);
    } else {
        s = s.replace('.', dec);
    }

    var decPos = s.indexOf(dec);
    if (prec >= 1 && decPos !== -1 && (s.length - decPos - 1) < prec) {
        s += new Array(prec - (s.length - decPos - 1)).join(0) + '0';
    } else if (prec >= 1 && decPos === -1) {
        s += dec + new Array(prec).join(0) + '0';
    }
    return s;
}

function angka(nilai) {
    if (nilai == null) {
        nilai = '0';
    }
    var a = nilai.split(',').join('');
    var b = eval(a);
    return b;
}

function load_grid(elemen = "#gridContainer", url, key, filename, columnDefs) {
    if ($(elemen).length > 0) {
        var db = DevExpress.data.AspNet.createStore({
            key: key,
            loadMethod: "post",
            loadUrl: url
        });

        var gridOptions = {
            dataSource: {
                store: db
            },
            columnMinWidth: 35,
            columnMinWidth: 35,
            columnAutoWidth: true,
            allowColumnResizing: true,
            showColumnLines: true,
            showBorders: false,
            columnChooser: {
                enabled: true,
                height: 180,
                width: 400,
                emptyPanelText: 'A place to hide the columns',
                mode: "select"
            },
            remoteOperations: true,
            onToolbarPreparing: toolbarPreparing,
            headerFilter: {
                visible: true
            },
            filterRow: {
                visible: true,
                applyFilter: "auto"
            },
            searchPanel: {
                visible: true,
                width: 240,
                placeholder: "Search..."
            },
            groupPanel: {
                visible: true
            },
            grouping: {
                autoExpandAll: true,
                contextMenuEnabled: true
            },
            summary: {
                groupItems: [{
                    column: key,
                    summaryType: "count",
                    displayFormat: "{0}",
                }]
            },
            paging: {
                pageSize: 10
            },
            pager: {
                showPageSizeSelector: true,
                allowedPageSizes: [10, 25, 50, 100],
                showInfo: true
            },
            export: {
                enabled: true,
                fileName: filename,
                allowExportSelectedData: true
            },
            onContentReady: function(e) {
                moveEditColumnToLeft(e.component);
            },
        };

        if (typeof columnDefs !== "undefined") {
            gridOptions.columns = columnDefs;
        }

        DevDatagrid = $("#gridContainer").dxDataGrid(gridOptions);

        function moveEditColumnToLeft(dataGrid) {
            dataGrid.columnOption("command:edit", {
                visibleIndex: -1,
                width: 80
            });
        }

        function toolbarPreparing(e) {
            var dataGrid = e.component;

            e.toolbarOptions.items.unshift({
                location: "before",
                widget: "dxButton",
                options: {
                    hint: "Add Records",
                    icon: "plus",
                    onClick: function(e) {
                        isUpdate = false;
                        tambahdata();
                    }
                }
            });
        }
    }
}

function row_grid(elemen = "#gridContainer", url, template, key, filename, columnDefs) {
    if ($(elemen).length > 0) {
        var db = DevExpress.data.AspNet.createStore({
            key: key,
            loadMethod: "post",
            loadUrl: url
        });

        var gridOptions = {
            dataSource: {
                store: db
            },
            rowTemplate: template,
            columnMinWidth: 35,
            columnMinWidth: 35,
            columnAutoWidth: true,
            allowColumnResizing: true,
            showColumnLines: true,
            showBorders: false,
            columnChooser: {
                enabled: true,
                height: 180,
                width: 400,
                emptyPanelText: 'A place to hide the columns',
                mode: "select"
            },
            remoteOperations: true,
            onToolbarPreparing: toolbarPreparing,
            headerFilter: {
                visible: true
            },
            filterRow: {
                visible: true,
                applyFilter: "auto"
            },
            searchPanel: {
                visible: true,
                width: 240,
                placeholder: "Search..."
            },
            groupPanel: {
                visible: true
            },
            grouping: {
                autoExpandAll: true,
                contextMenuEnabled: true
            },
            summary: {
                groupItems: [{
                    column: key,
                    summaryType: "count",
                    displayFormat: "{0}",
                }]
            },
            paging: {
                pageSize: 10
            },
            pager: {
                showPageSizeSelector: true,
                allowedPageSizes: [10, 25, 50, 100],
                showInfo: true
            },
            export: {
                enabled: true,
                fileName: filename,
                allowExportSelectedData: true
            },
            onContentReady: function(e) {
                moveEditColumnToLeft(e.component);
            },
        };

        if (typeof columnDefs !== "undefined") {
            gridOptions.columns = columnDefs;
        }

        DevDatagrid = $("#gridContainer").dxDataGrid(gridOptions);

        function moveEditColumnToLeft(dataGrid) {
            dataGrid.columnOption("command:edit", {
                visibleIndex: -1,
                width: 80
            });
        }

        function toolbarPreparing(e) {
            var dataGrid = e.component;

            e.toolbarOptions.items.unshift({
                location: "before",
                widget: "dxButton",
                options: {
                    hint: "Add Records",
                    icon: "plus",
                    onClick: function(e) {
                        isUpdate = false;
                        tambahdata();
                    }
                }
            });
        }
    }
}

function load_delete(url, where, reload_grid = '') {
    swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: !0,
        confirmButtonText: "Yes, delete it!"
    }).then(function(e) {
        $.ajax({
            url: url,
            type: "post",
            data: ({ where: where }),
            dataType: "json",
            success: function(json) {
                swal("Delete!", "Success Delete Record!", "success");
                if (reload_grid != '') {
                    $(reload_grid).dxDataGrid('instance').refresh();
                }

            },
            error: function() {
                swal("Oops... Something went wrong!", "Please Call IT Programer!", "error");
            },
            beforeSend: function() {
                swal({
                    title: "Processing...!",
                    text: "Please Wait",
                    onOpen: function() {
                        swal.showLoading()
                    }
                })
            }
        });
    })
}

function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for (var key in params) {
        if (params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}