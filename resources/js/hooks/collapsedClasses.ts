import { Class } from '@type/data';
import { Ref, ref } from 'vue';

export default function useCollapsedClasses(
    classes: Class[]
): [ Ref<boolean[], boolean[]>, (i: number) => void ] {
    const collapsedClasses = ref(classes.map(() => true));

    function switchCollapse(i: number) {
        collapsedClasses.value[i] = !collapsedClasses.value[i];
    };

    return [ collapsedClasses, switchCollapse ];
}