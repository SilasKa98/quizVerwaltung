function createLabelAndTextarea(createdField, fieldLabelText){

    let div = document.createElement("div");
    div.setAttribute("class", "mb-3");
    div.setAttribute("id", createdField+"Wrapper");

    let questionLabel = document.createElement("label");
    questionLabel.setAttribute("for", createdField);
    questionLabel.setAttribute("class", "form-label");
    questionLabel.innerHTML = fieldLabelText;
    
    let questionInput = document.createElement("textarea");
    questionInput.setAttribute("id", createdField);
    questionInput.setAttribute("name", createdField);
    questionInput.setAttribute("class", "form-control");

    div.append(questionLabel);
    div.append(questionInput);
    return div;
}

function createInputWithRadio(){
    let div = document.createElement("div");
    div.setAttribute("class", "mb-3");
    div.setAttribute("id", "optionsWrapper");

    let optionInput = document.createElement("input");
    optionInput.setAttribute("type", "text");
    optionInput.setAttribute("name", "options[]");
    optionInput.setAttribute("class", "form-control createQuestionOption");
    optionInput.setAttribute("onchange", "setCheckboxOrRadioValue(this)");

    let optionCheckbox = document.createElement("input");
    optionCheckbox.setAttribute("type", "radio");
    optionCheckbox.setAttribute("name", "correctOptions");
    optionCheckbox.setAttribute("class", "createQuestionOptionCheckbox");
  
    let delBtn = document.createElement("button");
    delBtn.setAttribute("class", "btn btn-danger btn-circle");
    delBtn.setAttribute("onclick", "delInputOption(this)");
    delBtn.setAttribute("type", "button");
    delBtn.innerHTML = "X";

    div.append(optionInput);
    div.append(optionCheckbox);
    div.append(delBtn);
    return div;
}

function createInputWithCheckbox(){
    let div = document.createElement("div");
    div.setAttribute("class", "mb-3");
    div.setAttribute("id", "optionsWrapper");

    let optionInput = document.createElement("input");
    optionInput.setAttribute("type", "text");
    optionInput.setAttribute("name", "options[]");
    optionInput.setAttribute("class", "form-control createQuestionOption");
    optionInput.setAttribute("onchange", "setCheckboxOrRadioValue(this)");

    let optionCheckbox = document.createElement("input");
    optionCheckbox.setAttribute("type", "checkbox");
    optionCheckbox.setAttribute("name", "correctOptions[]");
    optionCheckbox.setAttribute("class", "createQuestionOptionCheckbox");

    let delBtn = document.createElement("button");
    delBtn.setAttribute("class", "btn btn-danger btn-circle");
    delBtn.setAttribute("onclick", "delInputOption(this)");
    delBtn.setAttribute("type", "button");
    delBtn.innerHTML = "X";

    div.append(optionInput);
    div.append(optionCheckbox);
    div.append(delBtn);
    return div;
}

function createLabelAndButton(createdField, fieldLabelText, buttonText, onclickFunc){
    let div = document.createElement("div");
    div.setAttribute("class", "mb-3");
    div.setAttribute("id", createdField+"Wrapper");

    let questionLabel = document.createElement("label");
    questionLabel.setAttribute("for", createdField);
    questionLabel.setAttribute("class", "form-label");
    questionLabel.innerHTML = fieldLabelText;

    let addBtn = document.createElement("button");
    addBtn.setAttribute("class", "btn btn-success");
    addBtn.setAttribute("style", "display: block;margin-bottom: 1%;");
    addBtn.setAttribute("onclick", onclickFunc);
    addBtn.setAttribute("type", "button");
    addBtn.innerHTML = buttonText;

    div.append(questionLabel);
    div.append(addBtn);
    return div;
}

function createLabelAndSelect(createdField, fieldLabelText, selectOptions){

    let div = document.createElement("div");
    div.setAttribute("class", "mb-3");
    div.setAttribute("id", createdField+"Wrapper");

    let questionLabel = document.createElement("label");
    questionLabel.setAttribute("for", createdField);
    questionLabel.setAttribute("class", "form-label");
    questionLabel.innerHTML = fieldLabelText;

    let select = document.createElement("select");
    select.setAttribute("id", createdField);
    select.setAttribute("name", createdField);
    select.setAttribute("class", "form-control");

    for(let i=0;i<selectOptions.length;i++){
        let option = document.createElement("option");
        option.setAttribute("value", selectOptions[i]);
        option.innerHTML = selectOptions[i];
        select.append(option);
    }

    div.append(questionLabel);
    div.append(select);
    return div;
}


function createhiddenInput(name, value){
    let input = document.createElement("input");
    input.setAttribute("type", "hidden");
    input.setAttribute("name", name);
    input.setAttribute("value", value);
    return input;
}


function addInputAndRadio(e){
    let option = createInputWithRadio();
    e.after(option);
}


function addInputAndCheckbox(e){
    let option = createInputWithCheckbox();
    e.after(option);
}


function setCheckboxOrRadioValue(e){
    e.nextElementSibling.value = e.value;
}


function delInputOption(e){
    e.parentNode.remove();
}
