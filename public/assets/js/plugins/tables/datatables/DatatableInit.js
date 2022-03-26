class DatatableInit {

    constructor(route, data, columns) {
        this.initVar = $('.datatable-basic').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            responsive: true,
            autoWidth: true,
            ajax: {
                url: route,
                data: data
            },
            columnDefs: [
                {"searchable": false, "sortable": false, "targets": 0}
            ],
            columns: columns,
            pageLength: 100
            // stateSave: true
        });
    }

    init() {
        this.initVar();
    }

    draw() {
        this.initVar.draw();
    }
}

