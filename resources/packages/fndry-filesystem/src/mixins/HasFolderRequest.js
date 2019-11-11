
import {findIndex, merge, isObject} from 'lodash';

export default {

    mixins: [

    ],
    props: {
        folder: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            folderRoot: this.folder,
            folderTrail: [],
            folderIndex: null
        }
    },
    created(){

        if (this.folder.parent) {
            this.folderRoot = this.folder.parent;
            this.folderTrail.push(this.folderRoot);
        } else {
            this.folderRoot = this.folder;
        }
        this.goToFolder(this.folder);
    },
    methods: {
        goToFolder(folder){
            let index = findIndex(this.folderTrail, (f) => f.id === folder.id);
            if (index === -1) {
                this.folderTrail.push(folder);
            } else {
                this.folderTrail = [...this.folderTrail.slice(0, index + 1)];
            }
            this.folderIndex = this.folderTrail.length - 1;
            this.hideUpload();
        }
    }
}
