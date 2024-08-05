document.addEventListener("DOMContentLoaded", function () {
    // Get the necessary DOM elements
    const searchInput = document.getElementById("search-input");
    const projectFilter = document.getElementById("project-filter");
    const tasksTableBody = document.getElementById("tasks-table-body");
    const paginationLinks = document.getElementById("pagination-links");
    const sortLinks = document.querySelectorAll(".sort-link");
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    // Variables to store the current state
    let currentPage = 1;
    let currentQuery = "";
    let currentProjectId = "";
    let sortColumn = "id";
    let sortOrder = "desc";
    let taskToDelete = null;
    let taskToEdit = null;

    // Show the modal to create a new task when the "Add Task" button is clicked
    document
        .getElementById("add-task-btn")
        .addEventListener("click", function () {
            const modal = new bootstrap.Modal(
                document.getElementById("createTaskModal")
            );
            modal.show();
        });

    // Show the modal to edit or delete a task when the corresponding buttons are clicked
    document.addEventListener("click", function (event) {
        // If the user clicks the "Edit" button
        if (event.target && event.target.classList.contains("edit-btn")) {
            const taskId = event.target.dataset.taskId;
            fetch(`/tasks/${taskId}/edit`)
                .then((response) => response.json())
                .then((data) => {
                    const task = data.task;
                    // Populate the edit modal with task data
                    document.getElementById("edit-task-name").value = task.name;
                    document.getElementById("edit-task-content").value =
                        task.content;
                    document.getElementById("edit-task-project").value =
                        task.project_id || "";
                    document.getElementById("edit-task-id").value = task.id;
                    const modal = new bootstrap.Modal(
                        document.getElementById("editTaskModal")
                    );
                    modal.show();
                    taskToEdit = taskId;
                })
                .catch((error) => {
                    console.error("Error fetching task:", error);
                });
        }

        // If the user clicks the "Delete" button
        if (event.target && event.target.classList.contains("delete-btn")) {
            const taskId = event.target.dataset.taskId;
            taskToDelete = taskId;
            const taskName = event.target
                .closest("tr")
                .querySelector("td:nth-child(2)").textContent;
            // Show the delete confirmation modal with the task information
            const modalTitle = document.querySelector(
                "#deleteConfirmModalLabel"
            );
            const modalBody = document.querySelector(".modal-body");
            modalTitle.textContent = `Delete Task #${taskId}`;
            modalBody.textContent = `Are you sure you want to delete the task "${taskName}"?`;
            const modal = new bootstrap.Modal(
                document.getElementById("deleteConfirmModal")
            );
            modal.show();
        }
    });

    // Confirm task deletion after clicking the "Confirm Delete" button
    document
        .getElementById("confirm-delete-btn")
        .addEventListener("click", function () {
            if (taskToDelete) {
                fetch(`/tasks/${taskToDelete}`, {
                    method: "DELETE",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                })
                    .then((response) => response.json())
                    .then(() => {
                        // Remove the task row from the table and update the table after successful deletion
                        document
                            .querySelector(`tr[data-task-id="${taskToDelete}"]`)
                            .remove();
                        updateTable(
                            currentQuery,
                            currentProjectId,
                            currentPage,
                            sortColumn,
                            sortOrder
                        );
                    })
                    .catch((error) => {
                        console.error("Error deleting task:", error);
                    })
                    .finally(() => {
                        taskToDelete = null;
                        // Hide the delete confirmation modal
                        const modal = bootstrap.Modal.getInstance(
                            document.getElementById("deleteConfirmModal")
                        );
                        if (modal) {
                            modal.hide();
                        }
                    });
            }
        });

    // Function to update the task table based on search, pagination, and sorting parameters
    function updateTable(
        query,
        projectId,
        page = 1,
        column = "id",
        order = "desc"
    ) {
        query = query.trim();
        currentQuery = query;
        currentPage = page;
        currentProjectId = projectId;
        sortColumn = column;
        sortOrder = order;

        const url = new URL("/api/tasks/search", window.location.origin);
        url.searchParams.append("query", query);
        url.searchParams.append("project_id", projectId);
        url.searchParams.append("page", page);
        url.searchParams.append("sort_column", column);
        url.searchParams.append("sort_order", order);

        fetch(url.toString(), {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": csrfToken,
                "Content-Type": "application/json",
            },
            
        })
            .then((response) => response.json())
            .then((data) => {
                tasksTableBody.innerHTML = ""; // Clear current table rows
                if (data.data && Array.isArray(data.data)) {
                    // Add new task rows to the table
                    data.data.forEach((task) => {
                        const row = document.createElement("tr");
                        row.dataset.taskId = task.id;
                        row.innerHTML = `
                            <td>${task.id}</td>
                            <td>${task.name}</td>
                            <td>${task.content}</td>
                            <td>${task.project ? task.project.name : ""}</td>
                            <td>
                                <button class="btn btn-warning edit-btn" data-task-id="${
                                    task.id
                                }"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-danger delete-btn" data-task-id="${
                                    task.id
                                }"><i class="bi bi-trash"></i></button>
                            </td>
                        `;
                        tasksTableBody.appendChild(row);
                    });
                }

                // Update pagination links
                if (data.links) {
                    paginationLinks.innerHTML = data.links;

                    paginationLinks.querySelectorAll("a").forEach((link) => {
                        link.addEventListener("click", function (event) {
                            event.preventDefault();
                            const url = new URL(link.href);
                            const page = url.searchParams.get("page");
                            updateTable(
                                currentQuery,
                                currentProjectId,
                                page,
                                sortColumn,
                                sortOrder
                            );
                        });
                    });
                } else {
                    paginationLinks.innerHTML = "";
                }

                // Update sorting links based on the current column
                sortLinks.forEach((link) => {
                    const column = link.dataset.column;
                    const order = link.dataset.order;
                    link.classList.remove("sort-asc", "sort-desc");
                    if (column === sortColumn) {
                        link.classList.add(
                            order === "asc" ? "sort-asc" : "sort-desc"
                        );
                        link.dataset.order = order === "asc" ? "desc" : "asc";
                        link.querySelectorAll(".sort-icon").forEach((icon) => {
                            icon.style.display = "none";
                        });
                        link.querySelector(
                            `.sort-icon.${order}`
                        ).style.display = "inline";
                    } else {
                        link.dataset.order = "desc";
                        link.querySelectorAll(".sort-icon").forEach((icon) => {
                            icon.style.display = "none";
                        });
                    }
                });
            })
            .catch((error) => {
                console.error("Error updating table:", error);
            });
    }

    // Handle sorting link click events
    sortLinks.forEach((link) => {
        link.addEventListener("click", function (event) {
            event.preventDefault();
            const column = link.dataset.column;
            const order = link.dataset.order;
            updateTable(
                currentQuery,
                currentProjectId,
                currentPage,
                column,
                order
            );
        });
    });

    // Handle search input events
    searchInput.addEventListener("input", function () {
        updateTable(
            searchInput.value,
            currentProjectId,
            1,
            sortColumn,
            sortOrder
        );
    });

    // Handle project filter change events
    projectFilter.addEventListener("change", function () {
        updateTable(
            searchInput.value,
            projectFilter.value,
            1,
            sortColumn,
            sortOrder
        );
    });

    // Initialize the table with default data when the page first loads
    updateTable(
        currentQuery,
        currentProjectId,
        currentPage,
        sortColumn,
        sortOrder
    );

    // Handle form submission for creating a new task
    document
        .getElementById("create-task-form")
        .addEventListener("submit", function (event) {
            event.preventDefault();
            const name = document.getElementById("task-name").value.trim();
            const content = document
                .getElementById("task-content")
                .value.trim();
            const projectId = document.getElementById("task-project").value;

            fetch("/tasks", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": csrfToken,
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    name: name,
                    content: content,
                    project_id: projectId,
                }),
            })
                .then((response) => {
                    if (!response.ok) {
                        return response.json().then((errors) => {
                            throw errors;
                        });
                    }
                    return response.json();
                })
                .then(() => {
                    const modal = bootstrap.Modal.getInstance(
                        document.getElementById("createTaskModal")
                    );
                    modal.hide();
                    // Reload the page after successfully creating the task
                    window.location.reload();
                })
                .catch((errors) => {
                    displayErrors(errors, "create");
                });
        });

    // Handle form submission for editing a task
    document
        .getElementById("edit-task-form")
        .addEventListener("submit", function (event) {
            event.preventDefault();
            const id = document.getElementById("edit-task-id").value;
            const name = document.getElementById("edit-task-name").value.trim();
            const content = document
                .getElementById("edit-task-content")
                .value.trim();
            const projectId =
                document.getElementById("edit-task-project").value;

            fetch(`/tasks/${id}`, {
                method: "PUT",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": csrfToken,
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    name: name,
                    content: content,
                    project_id: projectId,
                }),
            })
                .then((response) => {
                    if (!response.ok) {
                        return response.json().then((errors) => {
                            throw errors;
                        });
                    }
                    return response.json();
                })
                .then(() => {
                    const modal = bootstrap.Modal.getInstance(
                        document.getElementById("editTaskModal")
                    );
                    modal.hide();
                    updateTable(
                        currentQuery,
                        currentProjectId,
                        currentPage,
                        sortColumn,
                        sortOrder
                    );
                })
                .catch((errors) => {
                    displayErrors(errors, "edit");
                });
        });

    // Function to display errors when they occur
    function displayErrors(errors, type) {
        let errorMessages = "";

        if (errors.errors) {
            for (const [field, messages] of Object.entries(errors.errors)) {
                messages.forEach((message) => {
                    errorMessages += `<p class="text-danger">${message}</p>`;
                });
            }
        }

        // Select the modal body to display the error messages
        const modalBody =
            type === "create"
                ? document.querySelector("#createTaskModal .modal-body")
                : document.querySelector("#editTaskModal .modal-body");

        const errorContainer = document.createElement("div");
        errorContainer.innerHTML = errorMessages;
        modalBody.insertBefore(errorContainer, modalBody.firstChild);
    }

    // Function to display errors when they occur
    function displayErrors(errors, type) {
        // Remove old errors before displaying new ones
        const modalBody =
            type === "create"
                ? document.querySelector("#createTaskModal .modal-body")
                : document.querySelector("#editTaskModal .modal-body");

        // Remove old error messages
        const oldErrorMessages = modalBody.querySelectorAll(".text-danger");
        oldErrorMessages.forEach((error) => error.remove());

        let errorMessages = "";

        if (errors.errors) {
            for (const [field, messages] of Object.entries(errors.errors)) {
                messages.forEach((message) => {
                    errorMessages += `<p class="text-danger">${message}</p>`;
                });
            }
        }

        // Create a new container for the error messages and add it to the modal body
        const errorContainer = document.createElement("div");
        errorContainer.innerHTML = errorMessages;
        modalBody.insertBefore(errorContainer, modalBody.firstChild);
    }
});
