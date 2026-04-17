export const BADGE_TIERS = [
    { score: 100000, tier: 'warrior', icon: '⚔️', color: '#FF9F43' },
    { score: 300000, tier: 'master', icon: '🎯', color: '#6C63FF' },
    { score: 500000, tier: 'legend', icon: '⭐', color: '#FF6B9D' },
    { score: 1000000, tier: 'god', icon: '👑', color: '#FFD700' },
];

export const getBadgeByScore = (language, score) => {
    if (!language || score < 100000) {
        return null;
    }

    const badge = [...BADGE_TIERS].reverse().find(b => score >= b.score);
    
    if (badge) {
        return {
            tier: badge.tier,
            title: `${badge.tier.charAt(0).toUpperCase() + badge.tier.slice(1)} of ${language}`,
            description: `${badge.score.toLocaleString()} points reached`,
            icon: badge.icon,
            color: badge.color,
            score: score,
            minScore: badge.score,
            isUnlocked: true,
        };
    }

    return null;
};

export const getAllBadgesByLanguage = (language, score) => {
    return BADGE_TIERS.map(badge => {
        const isUnlocked = score >= badge.score;
        return {
            tier: badge.tier,
            title: `${badge.tier.charAt(0).toUpperCase() + badge.tier.slice(1)} of ${language}`,
            description: `Reach ${badge.score.toLocaleString()} points`,
            icon: badge.icon,
            color: badge.color,
            minScore: badge.score,
            isUnlocked: isUnlocked,
            score: score,
        };
    });
};

export const getLanguageColor = (language) => {
    const colors = {
        'PHP': '#777BB4',
        'JavaScript': '#F7DF1E',
        'Python': '#3776AB',
        'Java': '#007396',
        'C++': '#00599C',
        'C#': '#239120',
        'Go': '#00ADD8',
        'Rust': '#CE422B',
        'TypeScript': '#3178C6',
        'Ruby': '#CC342D',
        'CSS': '#563D7C',
        'HTML': '#E34C26',
        'Shell': '#4EAA25',
        'SQL': '#336791',
        'Swift': '#FA7343',
        'Kotlin': '#7F52FF',
    };

    return colors[language] || '#8B5CF6';
};
