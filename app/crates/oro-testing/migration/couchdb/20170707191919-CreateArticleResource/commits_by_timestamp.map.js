function (commit) {
    if (/^oro\.testing\.article\-/.test(commit._id) && commit.streamRevision) {
        emit(commit.iso_date, 1);
    }
}