let isedit = false;
$(document).ready(function () {
    tabel_role();
    tabel_role_tersedia();
});
function tabel_role_tersedia(){
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_role").DataTable({
            dom: 'lfrtip',
            searching: false,
            lengthChange: false,
            ordering: false,
            language: {
                "paginate": {
                    "first": '<i class="fa fa-angle-double-left"></i>',
                    "last": '<i class="fa fa-angle-double-right"></i>',
                    "next": '<i class="fa fa-angle-right"></i>',
                    "previous": '<i class="fa fa-angle-left"></i>',
                },
            },
            scrollCollapse: true,
            scrollX: true,
            bFilter: false,
            bInfo: true,
            ordering: false,
            bPaginate: true,
            bProcessing: true,
            serverSide: true,
            ajax: {
                "url": baseurlapi + '/role/daftarrole',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $('#kotak_pencarian_role').val();
                    d.start = 0;
                    d.length = 10;
                },
                "dataSrc": function(json) {
                    let detailData = json.data;
                    let mergedData = detailData.map(item => {
                        return {
                            ...item,
                            recordsFiltered: json.recordsFiltered,
                        };
                    });
                    return mergedData;
                }
            },
            infoCallback: function(settings) {
                if (typeof settings.json !== "undefined") {
                    const currentPage = Math.floor(settings._iDisplayStart / settings._iDisplayLength) + 1;
                    const recordsFiltered = settings.json.recordsFiltered;
                    const infoString = 'Halaman ke: ' + currentPage + ' Ditampilkan: ' + 10 + ' Jumlah Data: ' + recordsFiltered + ' data';
                    return infoString;
                }
            },
            pagingType: "full_numbers",
            columnDefs: [{
                defaultContent: "-",
                targets: "_all"
            }],
            columns: [
                {
                    title: "Nama Role",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return capitalizeFirstLetter(row.name);
                        }
                        return data;
                    }
                },
                {
                    title: "Hak Akses",
                    data: "permissions",
                    render(data, type, row) {
                        const permissionList = typeof data === 'string'
                            ? data.split(',').map(value => value.trim()).filter(Boolean)
                            : Array.isArray(data)
                                ? data.map(value => String(value).trim()).filter(Boolean)
                                : [];

                        if (permissionList.length === 0) {
                            return `<span class="badge bg-danger me-1">Akses Semua Fitur MCU Artha Medica</span>`;
                        }

                        return permissionList
                                .map(permission => `<span class="badge bg-primary me-1">${capitalizeFirstLetter(permission)}</span>`)
                                .join('');
                    }
                },
                {
                    title: "Aksi",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return "<div class=\"d-flex justify-content-between gap-2\"><button class=\"btn btn-primary w-100\" onclick=\"editrole('" + row.id + "','" + row.name + "', '" + row.description + "','"+ row.group+"')\"><i class=\"fa fa-edit\"></i> Edit Role</button><button class=\"btn btn-danger w-100\" onclick=\"hapusrole('" + row.id + "','" + row.name + "', '"+row.permissions+"')\"><i class=\"fa fa-trash-o\"></i> Hapus Role</button></div>";
                        }
                        return data;
                    }
                },
            ]
        });
    }); 
}
function tabel_role(){
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_permission_tersedia").DataTable({
            searching: false,
            lengthChange: false,
            ordering: false,
            bFilter: false,
            bProcessing: true,
            serverSide: true,
            scrollX: $(window).width() < 768 ? true : false,
            pageLength: $('#data_ditampilkan').val(),
            pagingType: "full_numbers",
            columnDefs: [{
                defaultContent: "-",
                targets: "_all"
            }],
            language: {
                "paginate": {
                    "first": '<i class="fa fa-angle-double-left"></i>',
                    "last": '<i class="fa fa-angle-double-right"></i>',
                    "next": '<i class="fa fa-angle-right"></i>',
                    "previous": '<i class="fa fa-angle-left"></i>',
                },
            },
            ajax: {
                "url": baseurlapi + '/permission/daftarhakakses',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $('#kotak_pencarian').val();
                    d.length = $('#data_ditampilkan').val();
                },
                "dataSrc": function(json) {
                    let detailData = json.data;
                    let mergedData = detailData.map(item => {
                        return {
                            ...item,
                            recordsFiltered: json.recordsFiltered,
                        };
                    });
                    return mergedData;
                }
            },
            infoCallback: function(settings) {
                if (typeof settings.json !== "undefined") {
                    const currentPage = Math.floor(settings._iDisplayStart / settings._iDisplayLength) + 1;
                    const recordsFiltered = settings.json.recordsFiltered;
                    const infoString = 'Hal Ke: ' + currentPage + ' Ditampilkan: ' + $('#data_ditampilkan').val() + ' Dari Total : ' + recordsFiltered + ' Data';
                    return infoString;
                }
            },
            columnDefs: [{
                defaultContent: "-",
                targets: "_all"
            }],
            columns: [
                {
                    title: "Group Izin",
                    data: "group",
                    visible: false
                },
                {
                    title: "Nama Perizinan",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return capitalizeFirstLetter(row.name);
                        }
                        return data;
                    }
                },
                {
                    title: "Keterangan",
                    className: $(window).width() < 768 ? 'dt-nowrap' : '',
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.description;
                        }
                        return data;
                    }
                },
                {
                    title: "Pilih Role",
                    width: "200px",
                    className: $(window).width() < 768 ? 'dt-nowrap' : '',
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            const permissionName = String(row.name || '').trim();
                            let buttonText = row.isSelected ? 'Jangan Pilih' : 'Pilih';
                            let buttonClass = row.isSelected ? 'btn-secondary' : 'btn-primary';
                            const checkboxHtml = '<input type="checkbox" class="form-check-input group-' + revertStringToLowerCase(row.group) + '" id="checkbox_'+row.id+'" name="checkbox_roles[]" data-permission-name="' + permissionName + '" onclick="toggleRowSelection(this.closest(\'tr\'))" style="display:none;">';
                            return '<button class="btn ' + buttonClass + ' btn-sm role-button" data-id="' + row.id + '" data-permission-name="' + permissionName + '" onclick="toggleRowSelection(this.closest(\'tr\'))">' +
                                buttonText +
                                '</button>' + checkboxHtml;
                        }
                        return data;
                    }
                }
            ],
            rowGroup: {
                dataSrc: 'group',
                startRender: function(rows, group) {
                    return $('<tr>')
                        .append('<td colspan="4">' + group + '</td>');
                }
            },
            drawCallback: function(settings) {
                let api = this.api();
                let rows = api.rows({page: 'current'}).nodes();
                let last = null;

                api.column(0, {page: 'current'}).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group">' +
                                '<td colspan="1"><strong>Kelompok Izin</strong></td>' +
                                '<td colspan="1"><strong>' + group + '</strong></td>' +
                                '<td colspan="1">' +
                                    '<button class="btn btn-primary btn-sm" onclick="selectAllInGroup(\'' + revertStringToLowerCase(group) + '\')">Pilih Grup '+group+'</button>' +
                                '</td>' +
                            '</tr>'
                        );
                        last = group;
                    }
                });

                $('#datatables_permission_tersedia tbody tr').each(function() {
                    const row = $(this);
                    const checkbox = row.find('input[type="checkbox"]');
                    if (checkbox.length > 0) {
                        const permissionName = String(checkbox.data('permission-name') || row.data('permission-name') || row.find('td').eq(1).text().trim()).trim();
                        row.attr('data-permission-name', permissionName);
                        applyRowSelectionState(row, checkbox.is(':checked'));
                    }
                });
            }
        });
    });
}
$("#data_ditampilkan").on('change', function() {
    $("#datatables_permission_tersedia").DataTable().page.len($(this).val()).draw();
    $("#datatables_permission_tersedia").DataTable().ajax.reload();
});
$('#kotak_pencarian').on('input', debounce(function() {
  $("#datatables_permission_tersedia").DataTable().ajax.reload();
}, 500));
$('#kotak_pencarian_role').on('input', debounce(function() {
  $("#datatables_role").DataTable().ajax.reload();
}, 500));
$('#tambah_role_baru').on('click', function() {
    isedit = false;
    $("#nama_role").val("");
    $("#keterangan_role").val("");
    uncheckall_datatables_permission_tersedia();
});
$('#simpan_role').on('click', function() {
    if ($("#nama_role").val() == "" || $("#keterangan_role").val() == "") {
        return createToast('Kesalahan Formulir','top-right', 'Silahkan isi nama role dan keterangan role terlebih dahulu jikalau ingin membuat role baru.', 'error', 3000);
    }
    let checkedCheckboxes = $('#datatables_permission_tersedia').find('input[type="checkbox"]:checked');
    if (checkedCheckboxes.length === 0 && $("#nama_role").val() !== "Super Admin") {
        return createToast('Kesalahan Pemilihan', 'top-right', 'Silakan pilih setidaknya satu hak akses untuk role ini.', 'error', 3000);
    }
    $("#simpan_role").html('<i class="fa fa-spinner fa-spin"></i> Sedang Menyimpan Data');
    let selectedPermissions = [];
    checkedCheckboxes.each(function() {
        const $checkbox = $(this);
        const permissionName = $checkbox.data('permission-name') || $checkbox.closest('tr').data('permission-name') || $checkbox.closest('tr').find('td').eq(1).data('permission-name') || $checkbox.closest('tr').find('td').eq(1).text().trim();
        const cleanPermissionName = String(permissionName || '').trim();
        if (cleanPermissionName) {
            selectedPermissions.push(cleanPermissionName);
        }
    });
    $.ajax({
        url: baseurlapi + (isedit ? '/role/editrole' : '/role/tambahrole'),
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
        },
        method: 'POST',
        data: {
            idrole: isedit ? $("#id_role").val() : "",
            name: $("#nama_role").val(),
            description: $("#keterangan_role").val(),
            permissions: selectedPermissions
        },
        success: function(response) {
            $("#nama_role").val("");
            $("#keterangan_role").val("");
            uncheckall_datatables_permission_tersedia();
            createToast('Sukses', 'top-right', 'Role berhasil disimpan. Silahkan lakukan masuk ulang ke sistem untuk melihat role yang baru saja dibuat kepada pengguna terkait.', 'success', 3000);
            $("#datatables_role").DataTable().ajax.reload();
            $("#simpan_role").html('<i class="fa fa-save"></i> Simpan Data');
            isedit = false;
        },
        error: function(xhr, status, error) {
            createToast('Error', 'top-right', xhr.responseJSON.message, 'error', 3000);
            $("#simpan_role").html('<i class="fa fa-save"></i> Simpan Data');
        }
    });
});
function hapusrole(idrole,namarole, hakaskes){
    if (idrole == "") {
        return createToast('Kesalahan Formulir','top-right', 'Silahkan tentukan ID Role untuk melakukan penghapusan role.', 'error', 3000);
    }  
    isedit = false;
    Swal.fire({ 
        html: '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="pt-2 fs-15"><h4>Konfirmasi Hapus Role '+capitalizeFirstLetter(namarole)+'</h4><p class="text-muted mx-4 mb-0">Daftar role <b>'+capitalizeFirstLetter(hakaskes)+'</b> akan dihapus, pastikan anda telah mengubah role pengguna yang terkait dengan role ini menjadi role lainnya. Jikalau tidak penggunak tidak dapat masuk kedalam sistem</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Informasi',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: baseurlapi + '/role/hapusrole',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                method: 'GET',
                data: {
                    idrole: idrole,
                    namarole: namarole,
                },
                success: function(response) {
                    createToast('Sukses', 'top-right', 'Role berhasil dihapus', 'success', 3000);
                    $("#datatables_role").DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    createToast('Error', 'top-right', 'Terjadi kesalahan saat menghapus role', 'error', 3000);
                }
            });
        }
    });
}
function applyRowSelectionState(row, isSelected) {
    const $row = $(row);
    if ($row.length === 0 || $row.find('input[type="checkbox"]').length === 0) {
        return;
    }

    const $button = $row.find('.role-button');
    const $checkbox = $row.find('input[type="checkbox"]');

    $row.toggleClass('selected', isSelected);
    $button.text(isSelected ? 'Jangan Pilih' : 'Pilih')
        .toggleClass('btn-secondary', isSelected)
        .toggleClass('btn-primary', !isSelected)
        .removeClass('btn-primary btn-secondary');

    if (isSelected) {
        $button.addClass('btn-secondary');
        $checkbox.prop('checked', true);
        $row.css({
            'background-color': 'orange',
            'color': 'white'
        }).find('td').css('color', 'white');
    } else {
        $button.addClass('btn-primary');
        $checkbox.prop('checked', false);
        $row.css({
            'background-color': '',
            'color': '#3D434A'
        }).find('td').css('color', '#3D434A');
    }
}

function uncheckall_datatables_permission_tersedia(){
    $('#datatables_permission_tersedia tbody tr').each(function() {
        const row = $(this);
        if (row.find('input[type="checkbox"]').length === 0) {
            return;
        }
        applyRowSelectionState(row, false);
    });
}
function toggleRowSelection(row) {
    const $row = $(row);
    if ($row.find('input[type="checkbox"]').length === 0) {
        return;
    }
    const nextState = !$row.hasClass('selected');
    applyRowSelectionState($row, nextState);
}

$('#tabel-role tbody').on('click', 'tr', function() {
    toggleRowSelection(this);
});

$('#tabel-role tbody').on('click', '.role-button', function(e) {
    e.stopPropagation();
    toggleRowSelection($(this).closest('tr'));
});

function selectAllInGroup(group) {
    let allSelected = true;
    $('.group-' + revertStringToLowerCase(group)).each(function() {
        let row = $(this).closest('tr');
        if (!row.hasClass('selected')) {
            allSelected = false;
            return false;
        }
    });
    $('.group-' + revertStringToLowerCase(group)).each(function() {
        let row = $(this).closest('tr');
        if (allSelected) {
            if (row.hasClass('selected')) {
                toggleRowSelection(row);
            }
        } else {
            if (!row.hasClass('selected')) {
                toggleRowSelection(row);
            }
        }
    });
}
function editrole(idrole, namarole, keteranganrole, group){
    $("#id_role").val(idrole);
    $("#nama_role").val(namarole);
    $("#keterangan_role").val(keteranganrole);
    uncheckall_datatables_permission_tersedia();
    isedit = true;
    $.ajax({
        url: baseurlapi + '/role/detailrole',
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
        },
        method: 'GET',
        data: {
            idrole: idrole
        },
        success: function(response) {
           let data = response.data;
           let permissions = data.permissions || [];
           const selectedPermissionNames = permissions.map(function(permission) {
               return String(permission && permission.name ? permission.name : '').trim();
           }).filter(Boolean);

           $('#datatables_permission_tersedia tbody tr').each(function() {
               const row = $(this);
               const checkbox = row.find('input[type="checkbox"]');
               if (checkbox.length === 0) {
                   return;
               }

               const rowPermission = String(checkbox.data('permission-name') || row.data('permission-name') || row.find('td').eq(1).text().trim()).trim();
               const isSelected = selectedPermissionNames.some(function(name) {
                   return revertStringToLowerCase(name) === revertStringToLowerCase(rowPermission);
               });

               checkbox.prop('checked', isSelected);
               row.attr('data-permission-name', rowPermission);
               applyRowSelectionState(row, isSelected);
           });
        },
    });
}   