import { createApp } from 'vue';
import LernmoduleEditor from '@/components/LernmoduleEditor.vue';
import { taskEditorStore, store } from '@/store';

taskEditorStore.initialize();
createApp(LernmoduleEditor).use(store).mount('#app');
