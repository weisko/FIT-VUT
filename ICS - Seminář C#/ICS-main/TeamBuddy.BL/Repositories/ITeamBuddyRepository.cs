using System;
using System.Collections.Generic;
using TeamBuddy.BL.Models;

namespace TeamBuddy.BL.Repositories
{
    public interface ITeamBuddyRepository
    {
        IEnumerable<TeamListModel> GetAllTeams();
        IEnumerable<TeamListModel> GetAllMyTeams(Guid userId);
        IEnumerable<UserListModel> GetAllUsers();
        IEnumerable<UserListModel> GetAllUsersInTeam(Guid teamId);
        IEnumerable<PostDetailModel> GetAllPosts();
        IEnumerable<PostDetailModel> GetAllPostsInTeam(Guid teamId);
        IEnumerable<CommentDetailModel> GetAllComments();
        IEnumerable<CommentDetailModel> GetAllCommentsInPost(Guid postId);
        TeamDetailModel GetByName(string name);
        TeamDetailModel GetTyamById(Guid teamId);
        PostDetailModel GetPostById(Guid Id);
        UserDetailModel GetByEmail(string email);
        UserDetailModel GetByUsername(string username);
        TeamDetailModel Create(TeamDetailModel team);
        UserDetailModel Create(UserDetailModel user);
        PostDetailModel Create(PostDetailModel post, Guid teamId);
        CommentDetailModel Create(CommentDetailModel comment);
        void AddUserToTeam(UserDetailModel user, Guid teamId);
        void RemoveUserFromTeam(UserDetailModel user, Guid teamId);
        void UpdateUser(UserDetailModel user);
        void DeleteTeam(Guid id);
        void DeleteUser(Guid id);
        void DeletePost(Guid id);
        void DeleteComment(Guid id);
    }
}
