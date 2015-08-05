function redirectPage(page){
    window.location = page;
}

function randomNum(inf, sup){
    numPos = sup - inf;
    aleat = Math.random() * numPos;
    aleat = Math.round(aleat);
    return parseInt(inf) + aleat;
}

function renderStatus (data, metadata, record, rowIndex, columnIndex, store) {
    return "<span style=\"color: " + ((record.data.IMPORT == 1)? "#005E20" : "#FF0000") + ";\">" + record.data.STATUS.toUpperCase() + "</span>";
}

