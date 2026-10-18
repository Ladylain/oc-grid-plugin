<div class="d-flex flex-row">
    <?php if ($filter): ?>
    <div class="flex-grow-1">
        <?= $filter->render() ?>
    </div>
    <?php endif ?>
    <div class="control-filter">
        <div class="filter-group filter-setup dropdown">
            <a href="javascript:;" class="" data-toggle="dropdown" data-bs-toggle="dropdown">
                <span>
                    <i class="ph ph-funnel-simple"></i>
                </span>
            </a>
            <div class="dropdown-menu" role="menu">
                <div role="presentation">
                    <div class="p-3">
                        <div class="form-group">
                            <label class="control-label">Sort by</label>
                            <select class="form-control">
                                <option value="name">Name</option>
                                <option value="date">Date</option>
                                <option value="size">Size</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Direction</label>
                            <select class="form-control">
                                <option value="asc">asc</option>
                                <option value="desc">desc</option>
                            </select>
                        </div>
                        <div class="text-nowrap">
                        <button class="btn-sm btn btn-secondary">Reset</button>
                        <button class="btn-sm btn btn-primary">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="<?= $cssClasses ?>" id="<?= $this->getId() ?>">
    <?= $this->makePartial('grid') ?>
</div>