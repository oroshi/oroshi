function (commit) {
    if (/^oro\.testing\.article\-/.test(commit._id)) {
        emit([ commit.streamId, commit.streamRevision ], 1);
    }
}