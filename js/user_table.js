/**
 * moodle-mod_groupformation JavaScript for editing group membership before saving to Moodle.
 * https://github.com/moodlepeers/moodle-mod_groupformation
 *
 *
 * @author Eduard Gallwas, Johannes Konert, René Röpke, Neora Wester, Ahmed Zukic, Stefan Jung
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

let TABLE_SIZE = 20;
let jquery = null

require(['jquery'], function ($) {
    $(document).ready(function () {

        let userData = $("#data").text();
        let data = JSON.parse(userData);
        jquery = $;
        selectPage(1, $);
        createPagination(data)
        createTableSize($);
    });
});

/**
 * get called if the user is changing the pagination index
 * @param page
 */
function selectPage(page, $) {
    // get user data from php
    let userData = document.getElementById("data").innerText;

    let data = JSON.parse(userData);
    let paginationArray = paginate(data, TABLE_SIZE, page)

    // get table column names from php
    let tableHeader = JSON.parse(document.getElementById("strings").innerText);
    tableHeader = tableHeader.table_columns_names;

    addTable(paginationArray, tableHeader, page);
}

/**
 * creates table
 * @param data
 * @param tableHeader
 * @param page
 */
function addTable(data, tableHeader, page) {
    let page_number = page - 1;
    let tableContent = document.getElementById("table_content");

    let oldTable = tableContent.getElementsByClassName("table");

    // delete old table for the pagination index is change
    oldTable.length > 0 ? tableContent.removeChild(oldTable[0]) : null;

    // create table
    let table = document.createElement('TABLE');
    table.className = "table table-hover";

    // create table header
    let tableHead = document.createElement('THEAD');
    tableHead.className = "thead-light"
    table.appendChild(tableHead)

    let tr = document.createElement('TR');
    tableHead.appendChild(tr);
    for (let k = 0; k < tableHeader.length; k++) {
        let th = document.createElement('TH');
        th.scope = "col";
        let divName = document.createElement('div');
        divName.innerHTML = tableHeader[k]
        th.appendChild(divName);
        tr.appendChild(th);
    }


    // create body
    let tableBody = document.createElement('TBODY');
    table.appendChild(tableBody);

    // add each item
    for (let i = 0; i < data.length; i++) {

        // check if user has no answers submitted yet
        let userId = data[i].length > 1 ? data[i][0].userid : data[i][0].id;

        tr = document.createElement('TR');
        tr.id = `background-${userId}`;
        tr.setAttribute("data", JSON.stringify(userId));

        tableBody.appendChild(tr);

        // add index
        let td = document.createElement('TD');
        td.appendChild(document.createTextNode(page_number * TABLE_SIZE + i + 1));
        td.id = `number-${userId}`;

        tr.appendChild(td);

        // add first name
        td = document.createElement('TD');
        td.appendChild(document.createTextNode(data[i][data[i].length > 1 ? 1 : 0].firstname));
        td.id = `firstname-${userId}`;
        tr.appendChild(td);

        // add last name
        td = document.createElement('TD');
        td.appendChild(document.createTextNode(data[i][data[i].length > 1 ? 1 : 0].lastname));
        td.id = `lastname-${userId}`;
        tr.appendChild(td);

        // add consent given
        td = document.createElement('TD');
        td.id = `consent-${userId}`;
        let consentIcon = data[i][0].consent !== undefined ? data[i][0].consent === 0 ? renderXIcon() : renderCheckIcon(): renderXIcon();
        td.insertAdjacentHTML("beforeend", consentIcon);
        td.setAttribute("name", JSON.stringify("consent"));
        td.setAttribute("data", JSON.stringify(userId))
        tr.appendChild(td);

        // progress bar
        let answerCount = data[i][0].answer_count;

        let maxAnswerCount = data[i][0].max_answer_count

        // percentage of answer count
        let pcg = Math.floor(answerCount / maxAnswerCount * 100);

        td = document.createElement('TD');
        let progress = document.createElement("div");
        progress.className = "progress";
        progress.style.border = '1px solid black';

        let value = document.createElement("span");
        value.id = `questionaire-value-${userId}`;
        value.className = "progress-value";
        value.innerText = isNaN(pcg) ? "0%" : pcg + "%";
        value.style.fontSize = "x-small";

        let progressBar = document.createElement("div");
        progressBar.id = `questionaire-${userId}`;

        progressBar.className = "progress-bar";
        progressBar.setAttribute('style', 'width:' + Number(pcg) + '%');
        progressBar.setAttribute("role", "progressbar");
        progressBar.setAttribute("aria-valuenow", answerCount);
        progressBar.setAttribute("aria-valuemin", 0);
        progressBar.setAttribute("aria-valuemax", maxAnswerCount);

        td.appendChild(progress);
        progress.appendChild(progressBar)
        td.appendChild(value);
        tr.appendChild(td);

        // add answers submitted
        td = document.createElement('TD');
        td.id = `completed-${userId}`;
        let answeredIcon = data[i][0].completed === "0" ? renderXIcon() : renderCheckIcon();
        td.setAttribute("data", JSON.stringify(userId))
        td.insertAdjacentHTML("beforeend", answeredIcon);
        tr.appendChild(td);


        // delete answers button
        td = document.createElement('TD');

        let dropdown = document.createElement("div");
        dropdown.className = "dropdown";

        let button = document.createElement("button");

        // loading spinner
        let spinner = document.createElement("div");
        spinner.id = `spinner-${userId}`;
        spinner.setAttribute("role", "status");
        spinner.style.marginRight = "10px";

        button.appendChild(spinner)

        // set name of action button
        button.appendChild(document.createTextNode((JSON.parse(document.getElementById("strings").innerText)).actions));
        button.className = "btn btn-secondary dropdown-toggle";
        button.setAttribute("type", "button");
        button.setAttribute("data-toggle", "dropdown");
        button.setAttribute("aria-haspopup", "true");
        button.setAttribute("aria-expanded", "false");

        let dropdownMenu = document.createElement("div");
        dropdownMenu.className = "dropdown-menu";
        dropdownMenu.setAttribute("aria-labelledby", "dropdownMenuButton");

        // delete answers button
        let deleteButton = document.createElement("button");
        deleteButton.className = "dropdown-item";
        deleteButton.id = `delete-answers-button-${userId}`;
        // set button string
        deleteButton.appendChild(document.createTextNode((JSON.parse(document.getElementById("strings").innerText)).delete_answers));
        deleteButton.style.marginLeft = "10px";
        deleteButton.setAttribute('onclick', `deleteAnswers(${JSON.stringify(data[i][0])})`);

        dropdownMenu.appendChild(deleteButton);

        // exclude user button
        let excludeButton = document.createElement("button");
        excludeButton.id = `exclude-button-${userId}`;
        excludeButton.className = "dropdown-item";
        excludeButton.style.marginLeft = "10px";
        excludeButton.setAttribute('onclick', `excludeUser(${JSON.stringify({userid: userId, groupformation: data[i][0].groupformation, excluded: data[i][0].excluded})})`);

        dropdownMenu.appendChild(excludeButton)

        button.appendChild(dropdownMenu);

        td.appendChild(button);
        tr.appendChild(td);
    }
    tableContent.appendChild(table);

    data.forEach((user) => {
        handleStyleOfTable(user[0])
    })
}


/**
 * handles style of table
 * @param user
 * @param deleteAnswers
 */
function handleStyleOfTable(user, deleteAnswers = false){

    // check if user has no answers submitted yet
    let userId = user.userid !== undefined ? user.userid : user.id;

    // set color to grey if the user gets excluded or black if the user gets included
    let number = document.getElementById(`number-${userId}`);
    number.style.color = user.excluded == 1 ? "darkgrey" : null;

    let firstname = document.getElementById(`firstname-${userId}`);
    firstname.style.color = user.excluded == 1 ? "darkgrey" : null;

    let lastname = document.getElementById(`lastname-${userId}`);
    lastname.style.color = user.excluded == 1 ? "darkgrey" : null;

    let excludeButton = document.getElementById(`exclude-button-${userId}`);
    let langString = JSON.parse(document.getElementById("strings").innerText);
    excludeButton.innerText= user.excluded == 0 ? langString.exclude_user : langString.include_user;

    // check if user has no answers submitted yet
    if(user.excluded === undefined){
        excludeButton.disabled = true;
    }

    // background color
    let background = document.getElementById(`background-${userId}`);
    background.style.backgroundColor = user.excluded == 1 ? "lightgrey" : null;

    if(user.answer_count == 0 || deleteAnswers){
        let progressBar = document.getElementById(`questionaire-${userId}`);
        progressBar.style.width = "0%";
        // progressBar.innerHTML = "0";

        let value = document.getElementById(`questionaire-value-${userId}`);
        value.innerText = "0%";

        let completed = document.getElementById(`completed-${userId}`);
        completed.innerHTML = renderXIcon();

        let deleteAnswersButton = document.getElementById(`delete-answers-button-${userId}`);
        deleteAnswersButton.disabled = true;
    }

    // check if user has no answers submitted yet
    if (user.answer_count === undefined){
        let completed = document.getElementById(`completed-${userId}`);
        completed.innerHTML = renderXIcon();

        let deleteAnswersButton = document.getElementById(`delete-answers-button-${userId}`);
        deleteAnswersButton.disabled = true;
    }
}


/**
 * create pagination view
 * @param data
 */
function createPagination(data) {

    let pagination = document.getElementById("pagination");

    let numPages = data.length / TABLE_SIZE;

    // add an extra page
    if (numPages % 1 > 0)
        numPages = Math.floor(numPages) + 1;


    for (let i = 0; i < numPages; i++) {
        let page = document.createElement("li");
        page.className = "pager-item";
        page.dataset.index = i;

        let a = document.createElement("a")
        a.className = "page-link";
        a.text = i + 1;

        page.appendChild(a);

        if (i === 0)
            page.className = "page-item active";

        page.addEventListener('click', function () {
            let parent = this.parentNode;
            let items = parent.querySelectorAll(".page-item");
            for (let x = 0; x < items.length; x++) {
                items[x].className = "page-item"
            }
            this.className = "page-item active";
            let index = parseInt(this.dataset.index);

            // scroll to top of the table
            let tableContent = document.getElementById("content");
            tableContent.scrollIntoView();

            // change table page
            selectPage(index + 1);
        });
        pagination.appendChild(page);
    }
}

/**
 * calculate pagination index
 * @param array
 * @param page_size
 * @param page_number
 * @returns {*}
 */
function paginate(array, page_size, page_number) {
    // human-readable page numbers usually start with 1, so we reduce 1 in the first argument
    return array.slice((page_number - 1) * page_size, page_number * page_size);
}

/**
 * create selector for choosing the table size
 * @param $ jquery
 */
function createTableSize($) {
    let tableSize = document.getElementById("table_size");

    let dropdown = document.createElement("div");
    dropdown.className = "dropdown";

    let button = document.createElement("button");

    let buttonTextNode = document.createTextNode(TABLE_SIZE.toString());

    button.appendChild(buttonTextNode);
    button.id = "table_size_button";
    button.className = "btn btn-secondary dropdown-toggle";
    button.setAttribute("type", "button");
    button.setAttribute("data-toggle", "dropdown");
    button.setAttribute("aria-haspopup", "true");
    button.setAttribute("aria-expanded", "false");

    let dropdownMenu = document.createElement("div");
    dropdownMenu.className = "dropdown-menu";
    dropdownMenu.setAttribute("aria-labelledby", "dropdownMenuButton");


    let tableSizes = [20, 50, 100];

    tableSizes.forEach((size) => {
        let div = document.createElement("div");
        let tableSize = document.createTextNode(size.toString());
        div.onclick = () => {
            TABLE_SIZE = size;
            // reload table
            selectPage(1);

            // set new size to dropdown button
            let tableSizeButton = document.getElementById("table_size_button");
            tableSizeButton.firstChild.nodeValue = TABLE_SIZE.toString();

            // reload the pagination
            let userData = $("#data").text();
            let data = JSON.parse(userData);
            let pagination = document.getElementById("pagination");
            pagination.innerHTML = '';
            createPagination(data)
        }

        div.appendChild(tableSize)
        dropdownMenu.appendChild(div);
    });


    button.appendChild(dropdownMenu);

    dropdown.appendChild(button)
    tableSize.appendChild(dropdown)


}

/**
 * returns icon
 * @returns {string}
 */
const renderCheckIcon = () => {
    return "<svg width=\"1em\" height=\"1em\" viewBox=\"0 0 16 16\" class=\"bi bi-check-circle-fill\" fill=\"#43A047\" xmlns=\"http://www.w3.org/2000/svg\">\n" +
        "  <path fill-rule=\"evenodd\" d=\"M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z\"/>\n" +
        "</svg>"
}

/**
 * returns icon
 * @returns {string}
 */
const renderXIcon = () => {
    return "<svg width=\"1em\" height=\"1em\" viewBox=\"0 0 16 16\" class=\"bi bi-x-circle-fill\" fill=\"#e53935\" xmlns=\"http://www.w3.org/2000/svg\">\n" +
        "  <path fill-rule=\"evenodd\" d=\"M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.146-3.146a.5.5 0 0 0-.708-.708L8 7.293 4.854 4.146a.5.5 0 1 0-.708.708L7.293 8l-3.147 3.146a.5.5 0 0 0 .708.708L8 8.707l3.146 3.147a.5.5 0 0 0 .708-.708L8.707 8l3.147-3.146z\"/>\n" +
        "</svg>"
}

