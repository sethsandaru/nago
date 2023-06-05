/**
 * Solution
 *
 * Please scroll to bottom to see the invokers.
 *
 * Cheers!
 */

const minTs = (new Date('2019-01-01')).getTime();
const maxTs =  (new Date('2021-01-01')).getTime();

/**
 * I used simple hashmap to index the TNX
 */
const data = {
    indexByTs: {},
    indexByUser: {},
};

/**
 * 200 unq usernames
 */
const users = [];

function getRandomPoint() {
    const min = -30;
    const max = 30;
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function generateUsername() {
    const charSet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const maxLen = 10;
    const minLen = 6;
    let length = Math.floor(Math.random() * (maxLen - minLen + 1)) + minLen;
    let result = '';

    for (let i = 0; i < length; i++) {
        let randomIndex = Math.floor(Math.random() * charSet.length);
        result += charSet.charAt(randomIndex);
    }

    return result;
}

function generate200UniqueUsernames() {
    while (users.length < 200) {
        const str = generateUsername();
        if (users.includes(str)) {
            continue;
        }

        users.push(str);
    }
}

function pickRandomValue(array) {
    return array[Math.floor(Math.random() * array.length)];
}

const batchSize = 10000; // Number of records to generate in each batch
const totalRecords = 50000000; // Total number of records to generate

function generateBatch() {
    return new Promise((resolve) => {
        for (let j = 0; j < batchSize; j++) {
            const timeStamp = minTs + j;
            const user = pickRandomValue(users);
            const point = getRandomPoint();

            data.indexByTs[timeStamp] ??= [];
            data.indexByTs[timeStamp].push({
                user,
                point,
            });

            data.indexByUser[user] ??= [];
            data.indexByUser[user].push(point);
        }

        return resolve(true);
    });
}

async function generateRecords() {
    console.log('Building 50M records... Will take a little while.');

    const promises = [];

    for (let i = 0; i < totalRecords; i += batchSize) {
        promises.push(generateBatch());
    }

    console.log('Total Jobs to process concurrently:', promises.length)

    await Promise.all(promises);

    console.log('Generation complete.');
}

function findByTimestamp(ts) {
    return data.indexByTs[ts] || null;
}

function findByUser(username) {
    return data.indexByUser[username] || null;
}

function calculateTotalPointsAndPrint() {
    console.log('Total points of all users');
    Object.keys(data.indexByUser)
        .forEach((user) => {
            const points = (data.indexByUser[user] || [])
                .reduce((total, point) => {
                    total += point;

                    return total;
                }, 0);

            console.log(`User ${user} - Points: ${points}`);
        });
}

async function calculateTotalPointsAndPrintByDate(startDate, endDate) {
    const tsStart = (new Date(startDate)).getTime();
    const tsEnd = (new Date(endDate)).getTime();
    const userPointMap = {};
    const promises = [];

    for (let i = tsStart; i <= tsEnd; i += 10_000) {
        promises.push(new Promise((resolve) => {
            for (let j = i; j < (i + 10_000); j++) {
                const items = data.indexByTs[j] || [];

                if (!items.length) {
                    continue;
                }

                for (const item of items) {
                    userPointMap[item.user] ??= 0;
                    userPointMap[item.user] += item.point;
                }
            }

            return resolve(true);
        }));
    }

    await Promise.all(promises);

    console.log(`From ${startDate} to ${endDate}, here is the list:`)
    for (const [user, points] of Object.entries(userPointMap)) {
        console.log(`User ${user} - Points: ${points}`);
    }
}

/**
 * This wrapper func will help us to run the consumed-time of a function
 */
async function withTimeLogger(callback, actionName) {
    const start = Date.now();

    await callback();

    const end = Date.now();

    console.log(`${actionName} took: ${((end - start) / 1000)}s`);
}

/**
 * Main stuff here
 */
(async () => {
    // need to run this before generating data
    generate200UniqueUsernames();

    // R1
    await withTimeLogger(generateRecords, 'Generate 50M records');

    // R2
    await withTimeLogger(() => {
        const timestamp = 1546300800_002;
        console.log('Find by timestamp result:')
        console.log('Total records found:', findByTimestamp(timestamp).length);
        console.log('Find by non-exist timestamp result:')
        console.log(findByTimestamp(9999999));
    }, 'Find by Timestamp (ok and null)');

    // R3
    await withTimeLogger(() => {
        const user = pickRandomValue(users);
        console.log('Find by user result:')
        console.log('Total records found', findByUser(user).length);
        console.log('Find by non-exist user result:')
        console.log(findByUser('https://github.com/sethsandaru'));
    }, 'Find by user (ok and null)');

    // R4
    await withTimeLogger(() => {
        calculateTotalPointsAndPrint();
    }, 'Calculate total points of 200 users');

    // R5
    await withTimeLogger(async () => {
        const startDate = '2019-01-01';
        const endDate = '2019-01-03';
        await calculateTotalPointsAndPrintByDate(startDate, endDate);
    }, 'Calculate total points between 2 dates');
})();
