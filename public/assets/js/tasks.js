document.addEventListener("DOMContentLoaded", function () {
    // Lấy các phần tử DOM cần thiết
    const searchInput = document.getElementById("search-input");
    const projectFilter = document.getElementById("project-filter");
    const tasksTableBody = document.getElementById("tasks-table-body");
    const paginationLinks = document.getElementById("pagination-links");
    const sortLinks = document.querySelectorAll(".sort-link");
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    // Các biến để lưu trữ trạng thái hiện tại
    let currentPage = 1;
    let currentQuery = "";
    let currentProjectId = "";
    let sortColumn = "id";
    let sortOrder = "desc";
    let taskToDelete = null;
    let taskToEdit = null;

    // Hiển thị modal tạo mới nhiệm vụ khi nhấn nút "Add Task"
    document
        .getElementById("add-task-btn")
        .addEventListener("click", function () {
            const modal = new bootstrap.Modal(
                document.getElementById("createTaskModal")
            );
            modal.show();
        });

    // Hiển thị modal chỉnh sửa hoặc xóa nhiệm vụ khi người dùng nhấn vào các nút tương ứng
    document.addEventListener("click", function (event) {
        // Nếu người dùng nhấn nút "Edit"
        if (event.target && event.target.classList.contains("edit-btn")) {
            const taskId = event.target.dataset.taskId;
            fetch(`/tasks/${taskId}/edit`)
                .then((response) => response.json())
                .then((data) => {
                    const task = data.task;
                    // Điền dữ liệu của nhiệm vụ vào modal chỉnh sửa
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

        // Nếu người dùng nhấn nút "Delete"
        if (event.target && event.target.classList.contains("delete-btn")) {
            const taskId = event.target.dataset.taskId;
            taskToDelete = taskId;
            const taskName = event.target
                .closest("tr")
                .querySelector("td:nth-child(2)").textContent;
            // Hiển thị modal xác nhận xóa với thông tin nhiệm vụ cần xóa
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

    // Xác nhận xóa nhiệm vụ sau khi nhấn nút "Confirm Delete"
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
                        // Xóa dòng nhiệm vụ khỏi bảng và cập nhật bảng sau khi xóa thành công
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
                        // Ẩn modal xác nhận xóa
                        const modal = bootstrap.Modal.getInstance(
                            document.getElementById("deleteConfirmModal")
                        );
                        if (modal) {
                            modal.hide();
                        }
                    });
            }
        });

    // Hàm cập nhật bảng nhiệm vụ dựa trên các tham số tìm kiếm, phân trang và sắp xếp
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
            },
        })
            .then((response) => response.json())
            .then((data) => {
                tasksTableBody.innerHTML = ""; // Xóa các dòng hiện tại trong bảng
                if (data.data && Array.isArray(data.data)) {
                    // Thêm các dòng nhiệm vụ mới vào bảng
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

                // Cập nhật các liên kết phân trang
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

                // Cập nhật các liên kết sắp xếp dựa trên cột hiện tại
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

    // Xử lý sự kiện nhấn vào liên kết sắp xếp
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

    // Xử lý sự kiện tìm kiếm
    searchInput.addEventListener("input", function () {
        updateTable(
            searchInput.value,
            currentProjectId,
            1,
            sortColumn,
            sortOrder
        );
    });

    // Xử lý sự kiện thay đổi bộ lọc dự án
    projectFilter.addEventListener("change", function () {
        updateTable(
            searchInput.value,
            projectFilter.value,
            1,
            sortColumn,
            sortOrder
        );
    });

    // Khởi tạo bảng với dữ liệu mặc định khi trang được tải lần đầu
    updateTable(
        currentQuery,
        currentProjectId,
        currentPage,
        sortColumn,
        sortOrder
    );

    // Xử lý sự kiện submit form tạo nhiệm vụ mới
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
                    // Tải lại trang sau khi tạo nhiệm vụ thành công
                    window.location.reload();
                })
                .catch((errors) => {
                    displayErrors(errors, "create");
                });
        });

    // Xử lý sự kiện submit form chỉnh sửa nhiệm vụ
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

    // Hàm hiển thị lỗi khi có lỗi xảy ra
    function displayErrors(errors, type) {
        let errorMessages = "";

        if (errors.errors) {
            for (const [field, messages] of Object.entries(errors.errors)) {
                messages.forEach((message) => {
                    errorMessages += `<p class="text-danger">${message}</p>`;
                });
            }
        }

        // Chọn phần body của modal để hiển thị thông báo lỗi
        const modalBody =
            type === "create"
                ? document.querySelector("#createTaskModal .modal-body")
                : document.querySelector("#editTaskModal .modal-body");

        const errorContainer = document.createElement("div");
        errorContainer.innerHTML = errorMessages;
        modalBody.insertBefore(errorContainer, modalBody.firstChild);
    }
    // Hàm hiển thị lỗi khi có lỗi xảy ra
    function displayErrors(errors, type) {
        // Xóa các lỗi cũ trước khi hiển thị lỗi mới
        const modalBody =
            type === "create"
                ? document.querySelector("#createTaskModal .modal-body")
                : document.querySelector("#editTaskModal .modal-body");

        // Xóa các thông báo lỗi cũ
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

        // Tạo một phần tử chứa thông báo lỗi mới và thêm vào modal body
        const errorContainer = document.createElement("div");
        errorContainer.innerHTML = errorMessages;
        modalBody.insertBefore(errorContainer, modalBody.firstChild);
    }
});
