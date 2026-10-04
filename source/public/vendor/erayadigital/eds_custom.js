$(document).ready(function() {
    toggleDarkMode();
});
function toggleDarkMode() {
    if(localStorage.getItem('theme') === 'dark') {
        $('body').addClass('dark-mode');
        $('#moon-icon').hide(); 
        $('#sun-icon').show(); 
    } else {
        $('body').removeClass('dark-mode');
        $('#moon-icon').show(); 
        $('#sun-icon').hide(); 
    }

    $('#mode-toggle').click(function() {
        $('body').toggleClass('dark-mode');
        $('#moon-icon').toggle();
        $('#sun-icon').toggle();
    })
    if ($('body').hasClass('dark-mode')) {
        localStorage.setItem('theme', 'dark');
    } else {
        localStorage.setItem('theme', 'light');
    }
}
function dataURLToBlob(dataURL) {
    const byteString = atob(dataURL.split(',')[1]);
    const mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0];
    const arrayBuffer = new ArrayBuffer(byteString.length);
    const uint8Array = new Uint8Array(arrayBuffer);

    for (let i = 0; i < byteString.length; i++) {
        uint8Array[i] = byteString.charCodeAt(i);
    }

    return new Blob([arrayBuffer], { type: mimeString });
}
function capitalizeFirstLetter(input, split = '_', join = ' ') {
    return String(input ?? '')
        .split(split)
        .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
        .join(join);
}
function revertStringToLowerCase(input, split = ' ', join = '_') {
    return String(input ?? '')
        .split(split)
        .map(word => word.toLowerCase())
        .join(join);
}
function buildHierarchy(flatList) {
    const map = {};
    const roots = [];
    flatList.forEach(category => {
        map[category.id] = { ...category, children: [] };
    });
    flatList.forEach(category => {
        if (category.parent_id) {
            map[category.parent_id]?.children.push(map[category.id]);
        } else {
            roots.push(map[category.id]);
        }
    });
    return roots;
}
function addCategoryOptions(categories, depth = 0) {
    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = `${'—'.repeat(depth)} ${category.nama_kategori}`;
        selectElementKategori.appendChild(option);
        if (category.children && category.children.length > 0) {
            addCategoryOptions(category.children, depth + 1);
        }
    });
}
function formatAngkaSingkatan(angka) {
    if (angka >= 1_000_000_000) {
        return (angka / 1_000_000_000).toFixed(1).replace(/\.0$/, '') + 'M';
    } else if (angka >= 1_000_000) {
        return (angka / 1_000_000).toFixed(1).replace(/\.0$/, '') + 'Jt';
    } else if (angka >= 1_000) {
        return (angka / 1_000).toFixed(1).replace(/\.0$/, '') + 'Rb';
    } else {
        return angka.toString();
    }
}
function onloadfromnavigation(param_nomor_identitas, param_nama_peserta){
    if (param_nomor_identitas || param_nama_peserta){
        let newOption = new Option('['+param_nomor_identitas+'] - '+param_nama_peserta, param_nomor_identitas, true, false);
        $("#pencarian_member_mcu").append(newOption).trigger('change');
        $("#pencarian_member_mcu").val(param_nomor_identitas).trigger('change');
    }
}
function base64EncodeUnicode(str) {
  const bytes = new TextEncoder().encode(str);
  const binString = String.fromCodePoint(...bytes);
  return btoa(binString);
}
function debounce(func, wait) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);

        timeout = setTimeout(() => {
            func.apply(this, args);
        }, wait);
    };
}