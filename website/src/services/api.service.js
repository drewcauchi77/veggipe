export const getRecipes = async (itemsPerPage, token) => {
    let items = null;

    try {
        const response = await fetch(`http://localhost:8000/api/v1/recipes?itemsperpage=${itemsPerPage}`, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
        });

        if (response.ok) items = await response.json();
        return items;
    } catch (error) {
        console.error('Failed to fetch recipes:', error);
        return items;
    }
};