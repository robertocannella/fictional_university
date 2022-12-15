import axiosService from "./AxiosService";

export default class MyNotes {
    constructor() {

        if (document.querySelector('#my-notes')) {
            this.type = 'note';// The content type of this object
            this.delButtons = document.querySelectorAll('#my-notes .delete-note');
            this.editButtons = document.querySelectorAll('.edit-note');
            this.saveButtons = document.querySelectorAll('.update-note');
            this.createButton = document.querySelector('.submit-note');

            // Set all events
            this.events();

            // Bind all functions to this
            this.deleteNote = this.deleteNote.bind(this);
            this.editNote = this.editNote.bind(this);
            this.cancelEdit = this.cancelEdit.bind(this);
            this.updateNote = this.updateNote.bind(this);
            this.createNote = this.createNote.bind(this);
            this.events = this.events.bind(this)
        }
    }

    events(){
            this.delButtons.forEach((el)=>{
                el.addEventListener('click',(evt)=>this.deleteNote(el))
            })
            this.editButtons.forEach((el)=>{
                el.addEventListener('click',(evt)=>this.editNote(el))
            })
            this.saveButtons.forEach((el)=>{
                el.addEventListener('click',(evt)=>{this.updateNote(el)})
            })
            this.createButton.addEventListener('click',()=>{this.createNote()})
    }
    async createNote(){

        const title = document.querySelector('.new-note-title').value;
        const content = document.querySelector('.new-note-body').value;

        const newNote = {
            'title': title,
            'content': content,
            'status': 'publish'
        }
        // Create new Note in the database.

        try {
            let result = await axiosService.createSingle(this.type,newNote);
            if (result.status == 201) {
                console.log(result.data);
                console.log("Created")
                this.prependUL(newNote,result.data);
            }
        }catch (e){
            console.log(e.message)
            const message = document.querySelector('.note-limit-message');
            message.classList.add('active')
        }
    }
    prependUL(newNote,data){
        const titleField = document.querySelector('.new-note-title');
        const contentField = document.querySelector('.new-note-body');
        const notesUL = document.getElementById('my-notes');
        const newLI = document.createElement('li')

        // Clear Fields
        titleField.value = '';
        contentField.value ='';
        titleField.focus();

        // Prepend new note

        newLI.setAttribute('data-id',data.id)
        newLI.innerHTML = `
                   <input readonly class="note-title-field" type="text" value="${data.title.raw}">
                   <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
                   <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
                   <textarea readonly class="note-body-field" name="" id="" cols="30" rows="10">${data.content.raw}</textarea>
                   <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
        `
        notesUL.prepend(newLI);
        let delButton = newLI.querySelector('.delete-note')
        let editButton = newLI.querySelector('.edit-note')
        let saveButton = newLI.querySelector('.update-note')
        newLI.querySelector('.delete-note').addEventListener('click',()=>this.deleteNote(delButton))
        newLI.querySelector('.edit-note').addEventListener('click',()=>this.editNote(editButton))
        newLI.querySelector('.update-note').addEventListener('click',()=>this.updateNote(saveButton))

    }
    async deleteNote(el){
        // Grab the parent li element (that's where the id is)
        const thisNote = el.parentNode;
        try {
            let result = await  axiosService.deleteSingle(this.type,thisNote.dataset.id)

            if (result.status == 200){
                console.log("Successful Delete: " , result.data);
                if (result.data.userNoteCount < 5){
                    const message = document.querySelector('.note-limit-message');
                    message.classList.remove('active')
                }
                thisNote.remove();
            }
        }catch (e){
            if (e.message.includes('410'))
                console.log(`Item is Gone!  Error occurred: ${e.message}`)
            else
                console.log(e.message)
        }

    }

    async updateNote(el){
        // Grab the parent li element (that's where the id is)
        const thisNote = el.parentNode;
        const titleField = thisNote.querySelector('.note-title-field');
        const bodyField = thisNote.querySelector('.note-body-field');
        const title = titleField.value;
        const content = bodyField.value;


        const data = {
            'title': title,
            'content': content
        }

        // Update the note in the database.
        let result = await axiosService.updateSingle(this.type,thisNote.dataset.id,data)

        // Process the results
        if(result){
            console.log("Updated:" , result);
            this.cancelEdit(el);
        }else{
            alert(result);
        }

    }
    editNote(el){
        // Puts the note in editable mode
        // Grab the parent li element (that's where the id is)
        const thisNote = el.parentNode;

        const titleField = thisNote.querySelector('.note-title-field');
        titleField.removeAttribute('readonly');
        titleField.classList.add('note-active-field')

        const bodyField = thisNote.querySelector('.note-body-field');
        bodyField.removeAttribute('readonly');
        bodyField.classList.add('note-active-field')

        const saveNoteButton = thisNote.querySelector('.update-note');
        saveNoteButton.classList.add('update-note--visible')

        const editButton = thisNote.querySelector('.edit-note');
        editButton.innerHTML = `<i class="fa fa-times" aria-hidden="true"></i> Cancel`
        editButton.addEventListener('click',()=>{this.cancelEdit(el)})

    }
    cancelEdit(el){
        // Cancel the edit mode of a note - Basically undoes editNote()
        // Grab the parent li element (that's where the id is)
        const thisNote = el.parentNode;

        const titleField = thisNote.querySelector('.note-title-field');
        titleField.setAttribute('readonly',null);
        titleField.classList.remove('note-active-field')

        const bodyField = thisNote.querySelector('.note-body-field');
        bodyField.setAttribute('readonly',null);
        bodyField.classList.remove('note-active-field')

        const saveNoteButton = thisNote.querySelector('.update-note');
        saveNoteButton.classList.remove('update-note--visible')

        const editButton = thisNote.querySelector('.edit-note');
        editButton.innerHTML = `<i class="fa fa-pencil" aria-hidden="true"></i> Edit`
        editButton.addEventListener('click',()=>{this.editNote(el)})
    }


}

