// import { ref } from "vue";

// const isLoading = ref(false); // Tracks loader visibility

// export function useLoaderStore() {
//     return {
//         /**
//          * Show the loader if it is not already visible.
//          */
//         showLoader() {
//             if (!isLoading.value) {
//                 isLoading.value = true;
//                 console.log("Loader shown.");
//             }
//         },

//         /**
//          * Hide the loader only if it is currently visible.
//          */
//         hideLoader() {
//             if (isLoading.value) {
//                 isLoading.value = false;
//                 console.log("Loader hidden.");
//             }
//         },

//         /**
//          * Getter to access the loader's current state.
//          */
//         get isLoading() {
//             return isLoading;
//         },
//     };
// }

import { ref } from "vue";

// Loading state refs
const pageLoading = ref(false);
const backgroundLoading = ref(false);
const overlayLoading = ref(false);

// Loading types enum
export const LoadingType = {
    PAGE: 'page',
    BACKGROUND: 'background',
    OVERLAY: 'overlay',
    NONE: 'none'
};

export function useLoaderStore() {
    /**
     * Shows a specific type of loader
     * @param {string} type - The type of loader to show (from LoadingType enum)
     */
    const showLoader = (type = LoadingType.PAGE) => {
        console.log(type, 'type');
        
        switch (type) {
            case LoadingType.PAGE:
                if (!pageLoading.value) {
                    console.log("Attempting to show Page loader");
                    pageLoading.value = true;
                    console.log("Page loader shown");
                }
                break;
            case LoadingType.BACKGROUND:
                if (!backgroundLoading.value) {
                    console.log("Attempting to show Background loader");
                    backgroundLoading.value = true;
                    console.log("Background loader shown");
                }
                break;
            case LoadingType.OVERLAY:
                if (!overlayLoading.value) {
                    console.log("Attempting to show Overlay loader");
                    overlayLoading.value = true;
                    console.log("Overlay loader shown");
                }
                break;
        }
    };
    
    

    /**
     * Hides a specific type of loader
     * @param {string} type - The type of loader to hide (from LoadingType enum)
     */
    const hideLoader = (type = LoadingType.PAGE) => {
        switch (type) {
            case LoadingType.PAGE:
                if (pageLoading.value) {
                    console.log("Attempting to hide Page loader");
                    pageLoading.value = false;
                    console.log("Page loader hidden");
                }
                break;
            case LoadingType.BACKGROUND:
                if (backgroundLoading.value) {
                    console.log("Attempting to hide Background loader");
                    backgroundLoading.value = false;
                    console.log("Background loader hidden");
                }
                break;
            case LoadingType.OVERLAY:
                if (overlayLoading.value) {
                    console.log("Attempting to hide Overlay loader");
                    overlayLoading.value = false;
                    console.log("Overlay loader hidden");
                }
                break;
        }
    };
    /**
     * Hides all loaders at once
     */
    const hideAllLoaders = () => {
        pageLoading.value = false;
        backgroundLoading.value = false;
        overlayLoading.value = false;
        console.log("All loaders hidden");
    };

    return {
        // Actions
        showLoader,
        hideLoader,
        hideAllLoaders,

        // Getters
        isPageLoading: pageLoading,
        isBackgroundLoading: backgroundLoading,
        isOverlayLoading: overlayLoading,
        
        // Computed getter for any type of loading
        isAnyLoading: () => pageLoading.value || backgroundLoading.value || overlayLoading.value
    };
}