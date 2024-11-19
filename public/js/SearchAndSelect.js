/**
 * Transforms a list of checkboxes into a search and select list
 */
class SearchAndSelect {

    checkboxes = [];
    selected = [];
    searchInput = null;

    searchElement = null;
    checkboxesElement = null;
    selectListElement = null;
    containerElement = null;

    constructor(checkboxesElement) {
        this.checkboxesElement = checkboxesElement;
        this.checkboxes = checkboxesElement.querySelectorAll('input[type="checkbox"]');

        this.buildContainer();
        this.buildSelectList();
        this.buildSearch();
        this.buildCheckboxes();
        this.refreshSelectedCheckboxes();
        this.unfocusSearch();
    }

    // ----- SETUP ---- //

    buildContainer(){
        this.containerElement = document.createElement('div');
        this.containerElement.classList.add('search-and-select');

        this.checkboxesElement.parentNode.insertBefore(this.containerElement, this.checkboxesElement);
        this.containerElement.appendChild(this.checkboxesElement);
        this.checkboxesElement.classList.add('search-and-select__checkboxes');
    }

    buildSelectList(){
        this.selectListElement = document.createElement('ul');
        this.selectListElement.classList.add('search-and-select__select-list');

        this.containerElement.appendChild(this.selectListElement);
    }

    buildSearch(){
        this.searchElement = document.createElement('input');
        this.searchElement.setAttribute('type', 'text');
        this.searchElement.setAttribute('placeholder', 'Search...');
        this.searchElement.classList.add('search-and-select__search');

        this.containerElement.insertBefore(this.searchElement, this.checkboxesElement);

        this.searchElement.addEventListener('input', (event) => {
            this.search(event.target.value);
        });

        this.searchElement.addEventListener('focus', () => {
            this.focusSearch();
        });

        this.searchElement.addEventListener('blur', () => {
            this.unfocusSearch();
        });
    }

    buildCheckboxes(){
        this.checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', (event) => {
                if(event.target.checked){
                    this.select(checkbox);
                } else {
                    this.deselect(checkbox);
                }
            });

            let label = this.getCheckboxLabel(checkbox);
            label.addEventListener('mousedown', (e) => {
                e.stopPropagation();
                e.preventDefault();
                checkbox.click();
            });
            label.addEventListener('click', (e) => {
                e.preventDefault();
            });
        });

        this.checkboxesElement.classList.remove('search-and-select');
        this.checkboxesElement.classList.add('search-and-select__checkboxes');

        this.checkboxes.forEach(checkbox => {
            let label = this.getCheckboxLabel(checkbox);
            label.classList.add('search-and-select__checkbox-label');
            checkbox.classList.add('search-and-select__checkbox');
        });

    }

    refreshSelectedCheckboxes(){
        this.checkboxes.forEach(checkbox => {
            if(checkbox.checked){
                this.select(checkbox);
            }
        });
    }

    // ----- UTILS ---- //

    getCheckboxLabel(checkbox){
        return checkbox.nextElementSibling;
    }

    refreshSelectList(){
        this.selectListElement.innerHTML = '';

        this.selected.forEach(checkbox => {
            let label = this.getCheckboxLabel(checkbox).textContent;
            let li = document.createElement('li');
            li.textContent = label;
            li.classList.add('search-and-select__select-item');
            this.selectListElement.appendChild(li);

            li.addEventListener('click', () => {
                checkbox.checked = false;
                this.deselect(checkbox);
            });
        });
    }

    // ----- BEHAVIOUR ---- //

    select(checkbox){
        this.selected.push(checkbox);
        this.refreshSelectList();
    }

    deselect(checkbox){
        this.selected = this.selected.filter(selectedCheckbox => {
            return selectedCheckbox !== checkbox;
        });

        this.refreshSelectList();
    }

    search(searchString){
        console.log(searchString);
        if(searchString === ''){
            this.checkboxes.forEach(checkbox => {
                this.getCheckboxLabel(checkbox).style.display = 'block';
            });
            return;
        }

        this.checkboxes.forEach(checkbox => {
            let label = this.getCheckboxLabel(checkbox).textContent;
            if(label.toLowerCase().includes(searchString.toLowerCase())){
                this.getCheckboxLabel(checkbox).style.display = 'block';
            } else {
                this.getCheckboxLabel(checkbox).style.display = 'none';
            }
        });
    }

    focusSearch(){
        this.checkboxesElement.style.display = 'flex';
    }

    unfocusSearch(){
        this.checkboxesElement.style.display = 'none';
    }
}

// ----- Auto init ---- //

document.addEventListener('DOMContentLoaded', () => {
    let searchAndSelects = document.querySelectorAll('.search-and-select');
    searchAndSelects.forEach(searchAndSelect => {
        new SearchAndSelect(searchAndSelect);
    });
});