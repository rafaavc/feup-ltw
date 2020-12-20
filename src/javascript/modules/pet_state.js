export const getCurrentState = (state) => {
    if (state == 'adopted') return "Adopted";
    else if (state == 'ready') return "Ready for Adoption";
    else if (state == 'archived') return "Archived";
}